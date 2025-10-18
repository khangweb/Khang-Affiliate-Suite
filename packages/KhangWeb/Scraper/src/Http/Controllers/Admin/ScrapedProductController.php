<?php

namespace KhangWeb\Scraper\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use KhangWeb\Scraper\Repositories\ScrapedProductRepository; // Your ScrapedProductRepository
use KhangWeb\Scraper\DataGrids\ScrapedProductDataGrid; // Your ScrapedProductDataGrid
use KhangWeb\Scraper\Models\ScrapedProduct; // Your ScrapedProduct Model
use KhangWeb\Scraper\Models\ScrapingTemplate; // Assuming you have this model for the dropdown
use KhangWeb\Scraper\Jobs\ImportScrapedProductJob; // Đảm bảo đúng namespace của Job
use Illuminate\Support\Facades\Log; // Đảm bảo có dòng này
use Illuminate\Http\Request;

class ScrapedProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ScrapedProductRepository $scrapedProductRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ScrapedProductDataGrid::class)->toJson();
        }

        return view('scraper::admin.scraped_products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch scraping templates for dropdown (assuming ScrapingTemplate model exists)
        $scrapingTemplates = ScrapingTemplate::all(); // Adjust this if you have a repository for it

        return view('scraper::admin.scraped_products.create', compact('scrapingTemplates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = $this->validate(request(), [
                'name'                  => 'required|string|max:255',
                'status'                => 'required|in:pending,imported,failed',
                'error_message'         => 'nullable|string',
                'scraping_templates_id' => 'nullable|exists:scraping_templates,id', // Validate foreign key
                'ip'                    => 'nullable|ip',
                'url'                   => 'required|url',
                'raw_data'              => 'nullable|json',
            ]);
        $data['raw_data'] = json_decode($data['raw_data'] ?? '[]', true);

        try {
            ScrapedProduct::create($data);
            session()->flash('success', trans('scraper::app.scraped_products.create.create-success'));
            return redirect()->route('scraper.admin.scraped_products.index');
        } catch (\Exception $e) {
            session()->flash('error', trans('scraper::app.scraped_products.create.create-failed', ['error' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $scrapedProduct = $this->scrapedProductRepository->findOrFail($id);
        $scrapingTemplates = ScrapingTemplate::all(); // Fetch templates again for edit form
        return view('scraper::admin.scraped_products.edit', compact('scrapedProduct', 'scrapingTemplates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    $data = $this->validate($request, [
            'name'                  => 'required|string|max:255',
            'status'                => 'required|in:pending,imported,failed',
            'error_message'         => 'nullable|string',
            'scraping_templates_id' => 'nullable|exists:scraping_templates,id',
            'ip'                    => 'nullable|ip',
            'url'                   => 'required|url',
            'raw_data'              => 'nullable|json',
        ]);

        $data['raw_data'] = json_decode($data['raw_data'] ?? '[]', true);
        $scrapedProduct = ScrapedProduct::findOrFail($id);
 
        try {
            $scrapedProduct->update($data);
            session()->flash('success', trans('scraper::app.scraped_products.edit.update-success'));
            return redirect()->route('scraper.admin.scraped_products.index');
        } catch (\Exception $e) {
            session()->flash('error', trans('scraper::app.scraped_products.edit.update-failed', ['error' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->scrapedProductRepository->delete($id);

            session()->flash('success', trans('scraper::app.scraped_products.delete-success'));

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            session()->flash('error', trans('scraper::app.scraped_products.delete-failed', ['error' => $e->getMessage()]));
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Perform a mass destroy action.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $indices = request()->input('indices');

        if (!isset($indices)) {
            session()->flash('error', trans('admin::app.datagrid.mass-action.no-item-selected'));
            return redirect()->back();
        }

        foreach ($indices as $id) {
            try {
                $this->scrapedProductRepository->delete($id);
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.datagrid.mass-action.delete-failed', ['resource' => 'Scraped Product']));
                return redirect()->back();
            }
        }

        session()->flash('success', trans('admin::app.datagrid.mass-action.delete-success', ['resource' => 'Scraped Products']));

        return redirect()->back();
    }

     public function massDispatchForImport()
    {
        $indices = request()->input('indices');

        if (!isset($indices) || empty($indices)) {
            session()->flash('warning', trans('admin::app.datagrid.mass-action.no-item-selected'));
            return redirect()->back();
        }

        $dispatchedCount = 0;
        foreach ($indices as $id) {
            try {
                $scrapedProduct = $this->scrapedProductRepository->find($id);

                if ($scrapedProduct) {
                    // Chỉ dispatch nếu trạng thái là 'pending'
                    if ($scrapedProduct->status === 'pending') {
                        ImportScrapedProductJob::dispatch($scrapedProduct);
                        $dispatchedCount++;
                    } else {
                        Log::info("Scraped product ID: {$id} is not in 'pending' status. Skipping dispatch.");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to dispatch scraped product ID: {$id} for import. Error: " . $e->getMessage());
                // Không flash error cho từng item để tránh quá nhiều thông báo
            }
        }

        if ($dispatchedCount > 0) {
            session()->flash('success', trans('scraper::app.scraped_products.mass-action.dispatch-success', ['count' => $dispatchedCount]));
        } else {
            session()->flash('info', trans('scraper::app.scraped_products.mass-action.no-pending-to-dispatch'));
        }

        return redirect()->back();
    }

    public function massUpdateStatus(Request $request)
    {
        $indices = request()->input('indices');
       
        $status = $request->input('value');
         if (!isset($indices)) {
            session()->flash('error', trans('scraper::app.scraped_products.datagrid.update-fail'));
            return redirect()->back();
        }
         foreach ($indices as $id) {
            try {
                 $result = ScrapedProduct::where('id', $id)->update(['status' => $status]);
            } catch (\Exception $e) {

                return redirect()->back();
            }
        }
        return response()->json(['message' => __('scraper::app.scraped_products.datagrid.update-success')]);
    }
}

