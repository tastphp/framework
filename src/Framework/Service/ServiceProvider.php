<?php

namespace TastPHP\Framework\Service;

use TastPHP\Framework\Container\Container;

abstract class ServiceProvider
{
    public $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * register service
     * @return Service
     */
    abstract public function register();
}
