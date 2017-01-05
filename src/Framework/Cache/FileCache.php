<?php

namespace TastPHP\Framework\Cache;

use TastPHP\Framework\Service\ServiceMap;

/**
 * Class FileCache
 * @package TastPHP\Framework\Cache
 */
class FileCache extends ServiceMap
{
    /**
     * @return string
     */
    public static function getMap()
    {
        return 'localFileCache';
    }
}
