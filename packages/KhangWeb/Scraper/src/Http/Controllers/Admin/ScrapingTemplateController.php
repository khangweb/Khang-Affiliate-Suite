<?php

namespace KhangWeb\Scraper\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use KhangWeb\Scraper\Models\ScrapingTemplate;
use KhangWeb\Scraper\DataGrids\ScrapingTemplateDataGrid;
use Webkul\Admin\Http\Controllers\Controller;

class ScrapingTemplateController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return app(ScrapingTemplateDataGrid::class)->toJson();
        }

        return view('scraper::admin.scraping_templates.index');
    }

    public function create()
    {
        return view('scraper::admin.scraping_templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['fields'] = json_decode($data['fields'] ?? '[]', true);
        $data['attributes_name_selector'] = json_decode($data['attributes_name_selector'] ?? '[]', true);
        $data['attributes_value_selector'] = json_decode($data['attributes_value_selector'] ?? '[]', true);
        $data['active_button'] = json_decode($data['active_button'] ?? '[]', true);

        ScrapingTemplate::create($data);

        session()->flash('success', 'Scraping Template created successfully.');

        return redirect()->route('admin.scraper.scraping-templates.index');
    }

    public function edit($id)
    {
        $template = ScrapingTemplate::findOrFail($id);
        return view('scraper::admin.scraping_templates.create', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = ScrapingTemplate::findOrFail($id);
        $data = $request->all();
        $data['fields'] = json_decode($data['fields'] ?? '[]', true);
        $data['attributes_name_selector'] = json_decode($data['attributes_name_selector'] ?? '[]', true);
        $data['attributes_value_selector'] = json_decode($data['attributes_value_selector'] ?? '[]', true);
        $data['active_button'] = json_decode($data['active_button'] ?? '[]', true);

        $template->update($data);

        session()->flash('success', 'Scraping Template updated successfully.');

        return redirect()->route('admin.scraper.scraping-templates.index');
    }

    public function destroy($id)
    {
        ScrapingTemplate::findOrFail($id)->delete();

        session()->flash('success', 'Scraping Template deleted successfully.');

        return redirect()->route('admin.scraper.scraping-templates.index');
    }

    // Export JSON
    public function export()
    {
        $templates = ScrapingTemplate::all();
        return response()->json($templates);
    }

    // Import JSON
    public function import(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $row) {
            ScrapingTemplate::updateOrCreate(
                ['name' => $row['name']],
                $row
            );
        }

        return response()->json(['success' => true]);
    }

        public function massDestroy()
    {
        $indices = request()->input('indices');

        if (!isset($indices)) {
            session()->flash('error', trans('admin::app.datagrid.mass-action.no-item-selected'));
            return redirect()->back();
        }

        foreach ($indices as $id) {
            try {
                ScrapingTemplate::where('id' , $id)->delete();
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.datagrid.mass-action.delete-failed', ['resource' => 'Scraped Product']));
                return redirect()->back();
            }
        }

        session()->flash('success', trans('admin::app.datagrid.mass-action.delete-success', ['resource' => 'Scraped Products']));

        return redirect()->back();
    }
}
