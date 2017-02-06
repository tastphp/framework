<?php

namespace TastPHP\Framework\Service;

abstract class ServiceMap
{
    public static function getMap()
    {
        throw new \Exception('can not found the map!');
    }

    /**
     * static call
     *
     * @param  method
     * @param  parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        $map = static::getMap();
        $object = app()->singleton($map);

        if (!is_object($object)) throw new \RuntimeException($map.' can not be loaded , check your service provider config!');

        return call_user_func_array([$object, $method], $parameters);
    }
}
