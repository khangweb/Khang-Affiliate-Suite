<?php

namespace KhangWeb\ClientMessage\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Http;
use KhangWeb\ClientMessage\DataGrids\ClientMessageDataGrid;
use KhangWeb\ClientMessage\Models\ClientMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class ClientMessageController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ClientMessageDataGrid::class)->toJson();
        }

        return view('clientmessage::admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('clientmessage::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale   = app()->getLocale();
        $request->validate([
            'contact_person' => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'gender'         => 'required|in:male,female,other',
            'subject'        => 'required|string|max:255',
            'message'        => 'required|string',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'videos.*'       => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:10240',
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('contact_media/images', 'public');
                $imageUrls[] = Storage::disk('public')->url($path);
            }
        }

        $videoUrls = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('contact_media/videos', 'public');
                $videoUrls[] = Storage::disk('public')->url($path);
            }
        }

        $data = [
            'contact_person' => $request->input('contact_person'),
            'email'          => $request->input('email'),
            'gender'         => $request->input('gender'),
            'subject'        => $request->input('subject'),
            'message'        => $request->input('message'),
            'image_urls'     => $imageUrls,
            'video_urls'     => $videoUrls,
            'locale'         => $locale,
            'token'          => Str::random(40)
        ];
        $message = ClientMessage::create($data) ;
        $dataToSend =  $data ;
        $dataToSend['client_message_id'] = $message->id ;

        $apiUrl = config('client_message.host_api_url') . '/api/message';

        if (empty($apiUrl)) {
            session()->flash('error', 'Host API URL is not configured.');
            return redirect()->back()->withInput();
        }

        try {

            $domain = $request->getSchemeAndHttpHost();  
            $response = Http::withHeaders([
                    'X-Client-Domain' => $domain,
                    'X-Client-Email'  => $request->input('email')
                ])->post($apiUrl, $dataToSend);

            if ($response->successful()) {
                session()->flash('success', __('clientmessage::app.admin.success'));

                $responseData = $response->json();

                // Kiểm tra xem khóa 'request_id' có tồn tại không trước khi truy cập
                if (isset($responseData['request_id'])) {
                    $requestId = $responseData['request_id'];
                    $message->update(['request_id' => $requestId , 'status' => 'received' ]) ;
                } 

                session()->flash('success', __('clientmessage::app.admin.success'));
                return redirect()->route('admin.client_messages.index'); 
            } else {
                $responseBody = $response->json();
                $errorMessage = __('clientmessage::app.admin.error');

                if ($response->status() === 422 && isset($responseBody['errors'])) {
                    session()->flash('error', __('clientmessage::app.admin.data_fail'));
                    return redirect()->back()->withErrors($responseBody['errors'])->withInput();
                } elseif (isset($responseBody['message'])) {
                    $errorMessage = $responseBody['message'];
                }

                session()->flash('error', $errorMessage);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            session()->flash('error', __('clientmessage::app.admin.connect_fail'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        $message = ClientMessage::findOrFail($id);
        return view('clientmessage::admin.show', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function destroy(int $id)
    {
        $message = ClientMessage::findOrFail($id);
    
        if ($message->status === 'replied') {
            $message->delete();
    
            return response()->json([
                'message' => 'A message has been deleted successfully.',
                'status'  => true,
            ]);
        }
    
        return response()->json([
            'message' => 'This message cannot be deleted while it is awaiting a reply.',
            'status'  => false,
        ], 400);
    }

}
