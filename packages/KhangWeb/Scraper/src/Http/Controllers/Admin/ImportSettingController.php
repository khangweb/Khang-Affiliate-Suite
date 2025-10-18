<?php

namespace KhangWeb\Scraper\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Category\Models\Category;
use KhangWeb\Scraper\Models\ImportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade

class ImportSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $channels = Channel::all();
        $locales = Locale::all();
        $categories =Category::all()->toTree(); 
        $setting = ImportSetting::firstOrNew([]);
        return view('scraper::admin.settings', compact('channels', 'locales', 'categories', 'setting'));
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {

        $data=$this->validate($request, [
            'channel_code'              => 'required|string|exists:channels,code',
            'locale_code'               => 'required|string|exists:locales,code',
            'default_category_ids' => 'nullable|array',
            'meta_title_template'       => 'nullable|string|max:255',
            'meta_description_template' => 'nullable|string',
            'meta_keywords_template'    => 'nullable|string',
            'image_source'              => 'required|in:url,download', // <-- THÊM VALIDATION
            'video_source'              => 'required|in:url,download', // <-- THÊM VALIDATION
        ]);


        try {
            $setting = ImportSetting::firstOrNew([]);

            $setting->channel_code          = $request->input('channel_code');
            $setting->locale_code           = $request->input('locale_code');
            $setting->default_category_ids   = $request->input('default_category_ids');
            $setting->meta_title_template   = $request->input('meta_title_template');
            $setting->meta_description_template = $request->input('meta_description_template');
            $setting->meta_keywords_template    = $request->input('meta_keywords_template');
            $setting->image_source              = $request->input('image_source');
            $setting->video_source              = $request->input('video_source');

            $setting->save();

            session()->flash('success', trans('scraper::app.settings.save-message'));
        } catch (\Exception $e) {

            session()->flash('error', $e->getMessage());
        }

        // Chuyển hướng trở lại route index mà không có tham số channel_code
        return redirect()->route('admin.scraper.import.index'); // <-- Thay đổi ở đây
    }
}