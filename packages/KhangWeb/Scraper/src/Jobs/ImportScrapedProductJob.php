<?php

namespace KhangWeb\Scraper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use KhangWeb\Scraper\Models\ScrapedProduct;
use KhangWeb\Scraper\Services\ProductImport\ProductImporterService;
use KhangWeb\Scraper\Models\ProductUrl; // Import ProductUrl Model

class ImportScrapedProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The scraped product instance.
     *
     * @var \KhangWeb\Scraper\Models\ScrapedProduct
     */
    protected $scrapedProduct;

    /**
     * The ProductImporterService instance.
     *
     * @var \KhangWeb\Scraper\Services\ProductImport\ProductImporterService
     */
    protected $productImporter;

    /**
     * Create a new job instance.
     *
     * @param \KhangWeb\Scraper\Models\ScrapedProduct $scrapedProduct
     * @return void
     */
    public function __construct(ScrapedProduct $scrapedProduct)
    {
        $this->scrapedProduct = $scrapedProduct;
    }

    /**
     * Execute the job.
     *
     * @param \KhangWeb\Scraper\Services\ProductImport\ProductImporterService $productImporter
     * @return void
     */
    public function handle(ProductImporterService $productImporter)
    {
        $this->productImporter = $productImporter;

        try {
            // Bước 1: Kiểm tra xem URL đã tồn tại trong ProductUrl (link_source) chưa
            $existingProductUrl = ProductUrl::where('link_source', $this->scrapedProduct->url)->first();

            if ($existingProductUrl) {
                // Nếu URL đã tồn tại, cập nhật trạng thái và thông báo lỗi
                $this->scrapedProduct->update([
                    'status'        => 'imported', // Hoặc 'skipped' nếu bạn muốn một trạng thái khác cho các bản ghi đã tồn tại
                    'error_message' => 'URL already exists in the database.'
                ]);
                // Log thông báo để theo dõi
                return; // Dừng xử lý job này
            }

            // Nếu URL chưa tồn tại, tiến hành import sản phẩm
            $this->productImporter->import($this->scrapedProduct);

            // Cập nhật trạng thái sau khi import thành công
            $this->scrapedProduct->update(['status' => 'imported', 'error_message' => null]);

        } catch (\Exception $e) {
            // Cập nhật trạng thái lỗi nếu có ngoại lệ xảy ra trong quá trình import
            $this->scrapedProduct->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage()
            ]);
       }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        // Cập nhật trạng thái lỗi nếu job thất bại sau nhiều lần thử lại
        $this->scrapedProduct->update([
            'status'        => 'failed',
            'error_message' => 'Job failed after retries: ' . $exception->getMessage()
        ]);
    }
}