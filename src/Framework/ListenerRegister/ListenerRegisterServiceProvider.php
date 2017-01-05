<?php

namespace TastPHP\Framework\ListenerRegister;

use TastPHP\Framework\Service\ServiceProvider;

class ListenerRegisterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('listenerRegister', function () {
            return new ListenerRegisterService();
        });

        $this->app['listenerRegister']->register($this->app);
    }
}