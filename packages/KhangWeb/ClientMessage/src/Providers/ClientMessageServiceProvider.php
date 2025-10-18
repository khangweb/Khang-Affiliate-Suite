<?php

namespace KhangWeb\ClientMessage\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use KhangWeb\ClientMessage\Http\Middleware\ValidateClientMessageToken; // Import Middleware
use Illuminate\Routing\Router; // Import Router

class ClientMessageServiceProvider extends ServiceProvider
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

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'clientmessage');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'clientmessage');
                $this->mergeConfigFrom(
            __DIR__ . '/../Config/client_message.php',
            'client_message'
        );

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('clientmessage::admin.layouts.style');
        });

        // Middleware này sẽ được sử dụng trong file routes/api.php
        $router->aliasMiddleware('client_message.api_access', ValidateClientMessageToken::class);
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
    }
}