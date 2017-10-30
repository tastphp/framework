<?php

namespace TastPHP\Framework\Service;

/**
 * Class ServiceKernelProvider
 * @package TastPHP\Framework\Service
 */
class ServiceKernelProvider extends ServiceProvider
{
    /**
     * register serviceKernel
     */
    public function register()
    {
        if (class_exists('\\TastPHP\\Service\\ServiceKernel')) {
            $this->app['serviceKernel'] = \TastPHP\Service\ServiceKernel::instance();
            $this->app['serviceKernel']->setContainer($this->app);
            $this->app['serviceKernel']->setConnection($this->app['dbs']);
            if (!empty($this->app['serviceKernel']->getConnection()['master'])) {
                $this->app['serviceKernel']->getConnection()['master']->exec("SET names utf8mb4");
            }
        }
    }
}