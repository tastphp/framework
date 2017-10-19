<?php

namespace TastPHP\Framework\Cache;

/**
 * Class RedisCacheService
 * @package TastPHP\Framework\Cache
 */
class RedisCacheService
{
    protected $redis;

    /**
     * RedisCacheService constructor.
     * @param $redis
     */
    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    /**
     * 获取cache
     *
     * @param  cacheName
     * @return string|array|false
     */
    public function get($cacheName)
    {
        $value = $this->redis->get($cacheName);

        if ($value) {
            $value = gzinflate($value);
        }

        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData;
    }

    /**
     * @param $cacheName
     * @param $data
     * @param int $expireTime
     * @return mixed
     */
    public function set($cacheName, $data, $expireTime = 3600)
    {
        $data = (is_object($data) || is_array($data)) ? json_encode($data) : $data;

        $data = gzdeflate($data, 0);

        if (strlen($data) > 4096) {
            $data = gzdeflate($data, 6);
        }

        return $this->redis->set($cacheName, $data, $expireTime);
    }

    /**
     * get hash cache
     *
     * @param  hashKey
     * @param  key
     * @return string|array|false
     */
    public function hGet($hashKey, $key)
    {
        $value = $this->redis->hGet($hashKey, $key);
        if ($value) {
            $value = gzinflate($value);
        }

        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData;
    }

    /**
     * set hash cache
     *
     * @param  hashKey
     * @param  key
     * @param  data
     * @param  expireTime (int)
     * @return int
     */
    public function hSet($hashKey, $key, $data, $expireTime = 3600)
    {
        $data = (is_object($data) || is_array($data)) ? json_encode($data) : $data;

        $data = gzdeflate($data, 0);

        if (strlen($data) > 4096) {
            $data = gzdeflate($data, 6);
        }

        $status = $this->redis->hSet($hashKey, $key, $data);

        $this->redis->expire($hashKey, $expireTime);

        return $status;
    }

    /**
     * delete hash
     *
     * @param  hashKey
     * @param  key
     * @return boolean
     */
    public function hDel($hashKey, $key = null)
    {
        if ($key) {
            return $this->redis->hDel($hashKey, $key);
        }

        return $this->redis->hDel($hashKey);
    }

    /**
     * @return mixed
     */
    public function getRedis()
    {
        return $this->redis;
    }
}
