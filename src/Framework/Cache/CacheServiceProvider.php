<?php

namespace TastPHP\Framework\Cache;

use ServiceProvider;
use TastPHP\Framework\Cache\RedisCacheService;

/**
 * Class CacheServiceProvider
 * @package TastPHP\Framework\Cache
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return object
     */
    public function register()
    {
        $cache = 'redisCache';

        if ($cache == 'redisCache') {
            $this->app->singleton($cache, function () {
                if (!extension_loaded('redis')) {
                    return null;
                }
                return new RedisCacheService($this->app->singleton('redis'));
            });
        }
    }
}
