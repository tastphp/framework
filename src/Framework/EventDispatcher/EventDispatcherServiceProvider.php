<?php

namespace TastPHP\Framework\EventDispatcher;

use TastPHP\Framework\Service\ServiceProvider;

class EventDispatcherServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('eventDispatcher', function () {
            return new EventDispatcherService();
        });
    }
}