<?php

namespace TastPHP\Framework\Cache;

use ServiceProvider;
use Redis;

/**
 * Class RedisServiceProvider
 * @package TastPHP\Framework\Cache
 */
class RedisServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return object
     */
    public function register()
    {
        if (!extension_loaded('redis')) {
            return null;
        }

        $this->app->singleton('redis', function () {

            $config = \Config::parse('redis', [
                'host' => '127.0.0.1',
                'port' => 6379,
                'connect' => 'persistence',
                'auth' => ''
            ]);

            $redis = new Redis;

            if ($config['connect'] == 'persistence') {
                $redis->pconnect($config['host'], $config['port']);
            }

            if(empty($config['connect']) || $config['connect'] != 'persistence') {
                $redis->connect($config['host'], $config['port']);
            }

            if (isset($config['auth'])) {
                $redis->auth($config['auth']);
            }

            $redis->setOption(Redis::OPT_PREFIX, isset($config['prefix']) ? $config['prefix'] : '');

            return $redis;
        });
    }
}
