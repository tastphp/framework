<?php

namespace TastPHP\Framework\Cache;

use ServiceProvider;
use TastPHP\Framework\Cache\RedisCacheService;

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
