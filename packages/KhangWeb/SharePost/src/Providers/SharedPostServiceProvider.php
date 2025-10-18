<?php

namespace KhangWeb\SharedPost\Providers;

use KhangWeb\SharedPost\Http\Middleware\VerifyHostToken; // Import Middleware

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Router; // Import Router

class SharedPostServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sharedpost');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sharedpost');
        
        // Middleware này sẽ được sử dụng trong file routes/api.php
        $router->aliasMiddleware('verify.host.token', VerifyHostToken::class);

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('sharedpost::admin.layouts.style');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/sharedpost.php', 'sharedpost'
        );
    }
}