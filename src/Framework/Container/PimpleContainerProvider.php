<?php

namespace TastPHP\Framework\Container;

use TastPHP\Framework\Service\ServiceProvider;

/**
 * Class PimpleContainerProvider
 * @package TastPHP\Framework\Container
 */
class PimpleContainerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('container', function () {
            return new PimpleContainer($this->app);
        });
    }
}