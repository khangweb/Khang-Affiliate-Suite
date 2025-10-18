<?php

namespace KhangWeb\Scraper\Console\Commands;

use Illuminate\Console\Command;
use KhangWeb\Scraper\Models\ScrapedProduct;
use App\Jobs\ImportScrapedProductJob; // Hoặc KhangWeb\Scraper\Jobs\ImportScrapedProductJob

class ImportScrapedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:dispatch-imports'; // Đổi tên signature để tránh nhầm lẫn

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches scraped products to the import queue.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Dispatching scraped products to the import queue...');

        // Lấy danh sách các sản phẩm chưa được import.
        $scrapedProducts = ScrapedProduct::where('status', 'pending')->get();

        if ($scrapedProducts->isEmpty()) {
            $this->info('No new products to dispatch for import.');
            return;
        }

        $bar = $this->output->createProgressBar(count($scrapedProducts));
        $bar->start();

        foreach ($scrapedProducts as $scrapedProduct) {
            // Đẩy từng sản phẩm vào hàng đợi
            ImportScrapedProductJob::dispatch($scrapedProduct); // Dispatch the job
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All pending scraped products have been dispatched to the import queue!');
    }
}