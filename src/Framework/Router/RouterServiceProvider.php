<?php

namespace TastPHP\Framework\Router;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use TastPHP\Framework\Service\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use TastPHP\Framework\Event\AppEvent;

class RouterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $request = Request::createFromGlobals();
        $this->app['Request'] = $request;

        $psrRequest = $request;

        if (!$this->app->runningInConsole()) {
            $psr7Factory = new DiactorosFactory();
            $psrRequest = $psr7Factory->createRequest($request);

            $httpFoundationFactory = new HttpFoundationFactory();
            $psrRequest = $httpFoundationFactory->createRequest($psrRequest);
        }

        $this->app->singleton('eventDispatcher')->dispatch(AppEvent::REQUEST, new \TastPHP\Framework\Event\HttpEvent($psrRequest));

        $router = new TastRouter();
        $router->register($this->app);

        $this->app->singleton('router', function () {
            return RouterService::parseConfig($this->app->singleton('allRoutes'));
        });
    }
}