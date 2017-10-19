<?php

namespace TastPHP\Framework\Http;

use TastPHP\Framework\Service\ServiceProvider;

/**
 * Class RequestServiceProvider
 * @package TastPHP\Framework\Http
 */
class RequestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('symfonyRequest', function () {
            return Request::createFromGlobals();
        });

        $this->app['Request'] = $this->app['symfonyRequest'];
    }
}