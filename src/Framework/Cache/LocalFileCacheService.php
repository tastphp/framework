<?php

namespace TastPHP\Framework\Cache;

class LocalFileCacheService
{
    protected static $cache_dir = "var/cache/";

    /**
     * @param $cacheName
     * @param bool $cache_dir
     * @return mixed
     */
    public function get($cacheName, $cache_dir = false)
    {
        $cache_dir = $cache_dir == false ? self::$cache_dir : $cache_dir;
        $dir = $cache_dir."/".$cacheName;

        return include $dir;
    }

    /**
     * @param $cacheName
     * @param $data
     * @param bool $cache_dir
     */
    public function set($cacheName, $data, $cache_dir = false)
    {
        $cache_dir = $cache_dir == false ? self::$cache_dir : $cache_dir;
        $dir = $cache_dir."/".$cacheName;

        if (is_array($data)) {
            $data = var_export($data, true);
            $data = "<?php
return ".$data.";";
        }

        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "$part/")) {
                 mkdir($dir);
            }
        }

        file_put_contents("$dir/$file", $data);
    }

    /**
     * file isExist
     *
     * @param  cacheName(string)
     * @param  cache_dir(string)
     * @return boolean
     */
    public function isExist($cacheName, $cache_dir = false)
    {
        $cache_dir = $cache_dir == false ? self::$cache_dir : $cache_dir;
        $dir = $cache_dir.$cacheName;

        return file_exists($dir);
    }
}
