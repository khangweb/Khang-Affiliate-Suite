<?php
namespace KhangWeb\Scraper\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use KhangWeb\Scraper\Models\ScrapedProduct;

class ScrapedProductController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
            'url'    => 'required|url',
            'raw_data' => 'required|array'
        ]);

        try {
            $product = ScrapedProduct::updateOrCreate(
                ['url' => $data['url']],
                [
                    'name' => $data['name'] ?? null,
                    'scraping_templates_id'=> $request->input('template_id')?? null,
                    'ip' => $request->ip() ?? null,
                    'raw_data' => $data['raw_data'],
                ]
            );

            return response()->json([
                'message' => 'Scraped product saved successfully.',
                'data' => $product
            ]);
        } catch (\Exception $e) {


            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lưu dữ liệu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        return ScrapedProduct::latest()->paginate(20);
    }

    public function show($id)
    {
        return ScrapedProduct::findOrFail($id);
    }
}
