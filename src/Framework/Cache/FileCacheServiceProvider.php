<?php

namespace TastPHP\Framework\Cache;

use TastPHP\Framework\Service\ServiceProvider;

/**
 * Class FileCacheServiceProvider
 * @package TastPHP\Framework\Cache
 */
class FileCacheServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return object
     */
    public function register()
    {
        $this->app->singleton('localFileCache', function () {
            return new LocalFileCacheService();
        });
    }
}