<?php

namespace KhangWeb\Deeplink\Http\Controllers\Admin;

use Illuminate\Support\Facades\Event;
use KhangWeb\Deeplink\Models\ProductUrl;
use KhangWeb\Deeplink\DataGrids\ProductUrlDataGrid;
use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;

class ProductUrlController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductUrlDataGrid::class)->toJson();
        }
        return view('deeplink::admin.product_urls.index');
    }

    public function edit($id)
    {
        $productUrl = ProductUrl::findOrFail($id);

        return view('deeplink::admin.product_urls.edit', compact('productUrl'));
    }

    public function update(Request $request, $id)
    {
        $productUrl = ProductUrl::findOrFail($id);

        $request->validate([
            'link_source'=>'required|url',
            'link_aff' => 'nullable|url',
        ]);

        $productUrl->update([
             'link_source' => $request->input('link_source'),
            'link_aff' => $request->input('link_aff'),
        ]);

        session()->flash('success', __('deeplink::app.product-url.update-success'));

        return redirect()->route('admin.product-url.index');
    }
}
