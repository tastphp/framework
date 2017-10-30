<?php

namespace TastPHP\Framework\Doctrine;

use TastPHP\Framework\Service\ServiceProvider;

class DoctrineServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('doctrineService', function () {
            return new DoctrineService();
        });

        $this->app->singleton('doctrineService')->register($this->app);
        \Config::inject('dbs');
    }
}