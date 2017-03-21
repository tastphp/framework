<?php

namespace TastPHP\Framework\Cache;

class LocalFileCacheService
{
    protected static $cacheDir = "var/cache/";

    /**
     * @param $cacheName
     * @param bool $cacheDir
     * @return mixed
     */
    public function get($cacheName, $cacheDir = false)
    {
        $cacheDir = $cacheDir == false ? self::$cacheDir : $cacheDir;
        $dir = $cacheDir."/".$cacheName;

        if ($this->isExist($cacheName, $cacheDir)) return include $dir;
        return null;
    }

    /**
     * @param $cacheName
     * @param $data
     * @param bool $cacheDir
     */
    public function set($cacheName, $data, $cacheDir = false)
    {
        $cacheDir = $cacheDir == false ? self::$cacheDir : $cacheDir;
        $dir = $cacheDir."/".$cacheName;

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
     * @param  $cacheName(string)
     * @param  $cacheDir(string)
     * @return boolean
     */
    public function isExist($cacheName, $cacheDir = false)
    {
        $cacheDir = $cacheDir == false ? self::$cacheDir : $cacheDir;
        $dir = $cacheDir."/".$cacheName;

        return file_exists($dir);
    }
}
