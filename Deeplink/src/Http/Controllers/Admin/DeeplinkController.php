<?php

namespace KhangWeb\Deeplink\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use KhangWeb\Deeplink\DataGrids\DeeplinkTemplateDataGrid;
use KhangWeb\Deeplink\Models\DeeplinkTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade
class DeeplinkController extends Controller
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
     
            return app(DeeplinkTemplateDataGrid::class)->toJson();
        }

        return view('deeplink::admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('deeplink::admin.layouts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */


        public function store(Request $request)
    {
        $data = $request->all();

        $data['accepted_domains'] = array_filter(array_map('trim', explode(',', $request->input('accepted_domains'))));
        $data['should_encode_url'] = $request->has('should_encode_url');
        $data['apply_directly_to_product_url'] = $request->has('apply_directly_to_product_url');
        $data['status'] = $request->has('status');

        DeeplinkTemplate::create($data);

         session()->flash('success', __('deeplink::app.response.create-success'));
        return redirect()->route('admin.deeplink.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $template = DeeplinkTemplate::findOrFail($id);

         return view('deeplink::admin.layouts.create', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function update(Request $request, $id)
    {
        $template = DeeplinkTemplate::findOrFail($id);

        $data = $request->all();
        $data['accepted_domains'] = array_filter(array_map('trim', explode(',', $request->input('accepted_domains'))));
        $data['should_encode_url'] = $request->input('should_encode_url');
        $data['apply_directly_to_product_url'] = $request->input('apply_directly_to_product_url');
        $data['status'] = $request->input('status');

        $template->update($data);

        session()->flash('success', __('deeplink::app.response.update-success', ['name' => $template->name]));

        return redirect()->route('admin.deeplink.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

        public function destroy($id)
        {
            try {
                DeeplinkTemplate::where('id' , $id)->delete();

                session()->flash('success', trans('deeplink::app.delete-success'));

                return response()->json(['message' => 'Update Success']);
            } catch (\Exception $e) {
                session()->flash('error', trans('deeplink::app.delete-failed', ['error' => $e->getMessage()]));
                return response()->json(['message' => 'Error'], 500);
            }
        }


    public function massDelete()
    {
        $indices = request()->input('indices');

        if (!isset($indices)) {
            session()->flash('error', trans('deeplink::app.no-item-selected'));
            return redirect()->back();
        }
        foreach ($indices as $id) {
            try {
                 DeeplinkTemplate::where('id', $id)->delete();
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.delete-failed', ['resource' => 'DeepLink Template']));
                return redirect()->back();
            }
        }
        session()->flash('success', trans('admin::app.response.delete-success'));
        return redirect()->back();

    }

    public function massUpdateStatus(Request $request)
    {
        $indices = request()->input('indices');
       
        $status = (int) $request->input('value');
         if (!isset($indices)) {
            session()->flash('error', trans('deeplink::app.no-item-selected'));
            return redirect()->back();
        }
         foreach ($indices as $id) {
            try {
                 $result = DeeplinkTemplate::where('id', $id)->update(['status' => $status]);
            } catch (\Exception $e) {
                return redirect()->back();
            }
        }
        return response()->json(['message' => __('deeplink::app.mass-update-message')]);
    }

}
