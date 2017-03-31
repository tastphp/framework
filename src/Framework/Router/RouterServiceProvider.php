<?php

namespace TastPHP\Framework\Router;

use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Router\TastRouter;
use TastPHP\Framework\Router\RouterService;
use Symfony\Component\HttpFoundation\Request;
use TastPHP\Framework\Event\AppEvent;

class RouterServiceProvider extends ServiceProvider
{
    public function register()
    {   
        $request = Request::createFromGlobals();
        $this->app->setRequest($request);
        $this->app->singleton('eventDispatcher')->dispatch(AppEvent::REQUEST, new \TastPHP\Framework\Event\HttpEvent($request));

        $router = new TastRouter();
        $router->register($this->app);

        $this->app->singleton('router', function () {
            return RouterService::parseConfig($this->app->singleton('allRoutes'));
        });
    }
}