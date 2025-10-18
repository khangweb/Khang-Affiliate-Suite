<?php

namespace KhangWeb\ClientNotification\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Support\Facades\DB;
use KhangWeb\ClientNotification\Models\DomainToken;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $domain_tokens = DomainToken::first();

        $locale   = app()->getLocale();
        $perPage  = (int) $request->input('per_page', 5);
        $page     = (int) $request->input('page', 1);
        $domain = $request->getSchemeAndHttpHost();                 // Always use the real host domain
        $notifications = [] ;
        $meta = [] ;
        $links  = [];
        // URL được cấu hình trong config/services.php
        $apiUrl   = config('client_notification.host_api_url') . '/api/host-notifications';

        try {
            $response = Http::withHeaders([
                'X-Client-Domain' => $domain,          // Bắt buộc header cho middleware bên Host
            ])->acceptJson()->get($apiUrl, [
                'locale'   => $locale,
                'page'     => $page,
                'per_page' => $perPage,
                'status'   => ['active'],         // Nếu muốn lọc thêm trạng thái khác, sửa tại đây
            ]);

            // if (! $response->successful()) {
            //     return back()->withErrors(['error' => __('client_notification::app.notifications.error.error_01')]);
            // }

            $payload       = $response->json();
            $notifications = $payload['data']  ?? [];
            $meta          = $payload['meta']  ?? [];
            $links         = $payload['links'] ?? [];
      
        } catch (\Exception $e) {
       
           // return back()->withErrors(['error' => __('Connection error: ').$e->getMessage()]);
        }

        if ($request->ajax()) {
            return view('client_notification::admin.partials.notification-list', compact('notifications', 'meta', 'links' , 'domain_tokens'));
        }
        return view('client_notification::admin.index', compact('notifications', 'meta', 'links' , 'domain_tokens'));
    
    }

    public function check(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);

        $dataToSend = ['email' => $request->input('email')];
        $apiUrl     = config('client_notification.host_api_url') . '/api/check-customer';
        $domain     = $domain = $request->getSchemeAndHttpHost();  

        try {

            $response = Http::withHeaders([
                'X-Client-Domain' => $domain,
            ])->acceptJson()->get($apiUrl, $dataToSend);
            
            if (! $response->successful()) {
                return back()->withErrors(['email' => 'Không thể kết nối tới máy chủ host.'])->withInput();
            }

            $payload = $response->json();
            return back()->with([
                'api_result'    => $payload,
                'checked_email' => $request->email,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['email' => 'Có lỗi khi gọi host API: ' . $e->getMessage()])->withInput();
        }
    }

    public function getToken(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
        ]);
        $domain = $request->getSchemeAndHttpHost();  
        $locale   = app()->getLocale();

        try {
            $apiUrl = config('client_notification.host_api_url') . '/api/generate-token';

            $response = Http::withHeaders([
                'X-Client-Domain' => $request->domain,
            ])->acceptJson()->post($apiUrl, [
                'customer_id' => $request->customer_id,
                'domain'      => $domain,
                'locale'      => $locale
            ]);
            
            $payload = $response->json();
            
            if (! $response->successful()) {
                return back()->withErrors(['error' => $payload['message'] ?? __('client_notification::app.notifications.error.error_01')]);
            }

          
            return back()->with('success', $payload['message'] ?? '...');

        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['error' => __('client_notification::app.notifications.error.error_02') . $e->getMessage()]);
        }
    }

    public function markAsRead($id, Request $request)
    {
        $locale   = app()->getLocale();
        $domain    = $request->getHost();  // hoặc từ header nếu bạn dùng domain riêng
        $apiUrl   = config('client_notification.host_api_url') . '/api/mark-read';
        try {
            $response = Http::withHeaders([
                'X-Client-Domain' => $domain,    
                'X-Client-Identifier' => $domain,
            ])->post($apiUrl, [
                'notification_id' => $id,
                'locale'          => $locale
            ]);
            // Optional: ghi log hoặc xử lý response nếu cần
        } catch (\Exception $e) {
            report($e);
        }
    }

}
