<?php

namespace TastPHP\Framework\Cache;

use TastPHP\Framework\Service\ServiceMap;

/**
 * Class Cache
 * @package TastPHP\Framework\Cache
 */
class Cache extends ServiceMap
{
    public static function getMap()
    {
        return 'redisCache';
    }

    /**
     * @return mixed
     */
    public static function redis()
    {
        return \Kernel::getInstance()->singleton('redisCache');
    }
}
