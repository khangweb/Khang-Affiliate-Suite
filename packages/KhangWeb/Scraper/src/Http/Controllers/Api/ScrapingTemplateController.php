<?php
namespace KhangWeb\Scraper\Http\Controllers\Api;

use KhangWeb\Scraper\Models\ScrapingTemplate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log; // Thêm dòng này ở đầu file nếu chưa có
use Illuminate\Validation\ValidationException; // THÊM DÒNG NÀY

class ScrapingTemplateController extends Controller
{
    public function index()
    {
        return ScrapingTemplate::all();
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:scraping_templates,name',
            
            // Optional: Không validate chi tiết từng phần, vì ta gom hết lại
            'basic_information'    => 'nullable|array',
            'detail_information'   => 'nullable|array',
            'variant_selection'    => 'nullable|array',
        ]);

        $template = ScrapingTemplate::create([
            'name'   => $data['name'],
            'fields' => [
                'basic_information'   => $data['basic_information'] ?? [],
                'detail_information'  => $data['detail_information'] ?? [],
                'variant_selection'   => $data['variant_selection'] ?? [],
            ],
        ]);

        return response()->json($template->toArray() , 200);
    }

    public function show($id)
    {
        return ScrapingTemplate::findOrFail($id);
    }

    public function update(Request $request, $id)
    {

        $template = ScrapingTemplate::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:scraping_templates,name,' . $id, // bỏ qua id hiện tại
            'basic_information'    => 'nullable|array',
            'detail_information'   => 'nullable|array',
            'variant_selection'    => 'nullable|array',
        ]);

        $template->update([
            'name'   => $data['name'],
            'fields' => [
                'basic_information'   => $data['basic_information'] ?? [],
                'detail_information'  => $data['detail_information'] ?? [],
                'variant_selection'   => $data['variant_selection'] ?? [],
            ],
        ]);

        return response()->json($template->toArray() , 200);
    }

    public function destroy($id)
    {
        $template = ScrapingTemplate::findOrFail($id);
        $template->delete();

        return response()->json(['status' => 'deleted']);
    }
}
