<?php
namespace KhangWeb\Scraper\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use KhangWeb\Scraper\Http\Middleware\VerifyExtensionApiKey;

class ScraperServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'scraper');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'scraper');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('scraper::admin.layouts.style');
        });

        app('router')->aliasMiddleware('extension.key', VerifyExtensionApiKey::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \KhangWeb\Scraper\Console\Commands\ImportScrapedProducts::class,
            ]);
        }

        $this->loadAdminMenu();
    }

    public function register()
    {
        $this->registerConfig();

        $this->app->bind(
            \KhangWeb\Scraper\Repositories\ProductInventoryRepository::class
        );
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }

    protected function loadAdminMenu()
    {
        if (file_exists($adminMenu = __DIR__ . '/../Config/admin-menu.php')) {
            $config = $this->app['config']->get('menu');

            $this->app['config']->set('menu', array_merge_recursive($config, include $adminMenu));

        } 
    }
}
