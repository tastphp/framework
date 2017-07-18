<?php

namespace TastPHP\Framework\Router;

use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\HttpEvent;

class RouterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $request = $this->app['symfonyRequest'];

        if (!$this->app->runningInConsole()) {
            $request = $this->app['Request'];
        }

        $this->app->singleton('eventDispatcher')->dispatch(AppEvent::REQUEST, new HttpEvent($request));

        $router = new TastRouter();
        $router->register($this->app);

        $this->app->singleton('router', function () {
            return RouterService::parseConfig($this->app->singleton('allRoutes'));
        });
    }
}