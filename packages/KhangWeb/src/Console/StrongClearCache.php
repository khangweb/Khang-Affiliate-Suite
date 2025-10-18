<?php

namespace KhangWeb\ClearCache\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class StrongClearCache extends Command
{
    protected $signature = 'bagisto:clear-cache-strong';
    protected $description = 'Clear tất cả cache trong Bagisto';

    public function handle()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');

        $this->info('🎉 Tất cả cache đã được clear!');
    }
}
