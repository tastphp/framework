<?php

namespace TastPHP\Framework\Config;

use TastPHP\Framework\Service\ServiceProvider;

/**
 * Class ConfigServiceProvider
 * @package TastPHP\Framework\Config
 */
class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Config::getMap(), function () {
            return new ConfigService($this->app);
        });

        $this->app->singleton(Config::getMap())->register();
    }
}