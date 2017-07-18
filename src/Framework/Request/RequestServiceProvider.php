<?php

namespace TastPHP\Framework\Request;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use TastPHP\Framework\Service\ServiceProvider;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

/**
 * Class RequestServiceProvider
 * @package TastPHP\Framework\Request
 */
class RequestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('symfonyRequest', function () {
            return Request::createFromGlobals();
        });

        if (!$this->app->runningInConsole()) {
            $this->app->singleton('psr7Factory', function () {
                return new DiactorosFactory();
            });

            $this->app->singleton('httpFoundationFactory', function () {
                return new HttpFoundationFactory();
            });

            $this->app->singleton('Request', function () {
                return RequestAdapter::convertPsr7Request(Request::createFromGlobals());
            });
        } else {
            $this->app['Request'] = $this->app['symfonyRequest'];
        }
    }
}