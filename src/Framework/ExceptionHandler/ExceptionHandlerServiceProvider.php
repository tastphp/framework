<?php
namespace TastPHP\Framework\ExceptionHandler;

use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Handler\WhoopsExceptionsHandler;

class ExceptionHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $exceptionHandler = new WhoopsExceptionsHandler();
        $exceptionHandler->register($this->app);
    }
}