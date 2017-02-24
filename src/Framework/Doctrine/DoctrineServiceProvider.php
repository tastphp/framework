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

        //TODO
        if (class_exists('\\TastPHP\\Service\\ServiceKernel')) {
            $this->app['service_kernel'] = \TastPHP\Service\ServiceKernel::instance();
            $this->app['service_kernel']->setContainer($this->app);
            $this->app['service_kernel']->setConnection($this->app['dbs']);
            if (!empty($this->app['service_kernel']->getConnection()['master'])) {
                $this->app['service_kernel']->getConnection()['master']->exec("SET names utf8mb4");
            }
        }
    }
}