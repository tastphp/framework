<?php
namespace TastPHP\Framework\ExceptionHandler;

use TastPHP\Framework\Handler\ExceptionsHandler;
use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Handler\WhoopsExceptionsHandler;

class ExceptionHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app['debug']) {
            $exception = new ExceptionsHandler();
            $exception->bootstrap();
        }

        if ($this->app['debug']){
            $exceptionHandler = new WhoopsExceptionsHandler();
            $exceptionHandler->register();
        }
    }
}