<?php

namespace TastPHP\Framework\Logger;

use TastPHP\Framework\Service\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Logger::getMap(),function(){
            return new LoggerService();
        });
    }
}