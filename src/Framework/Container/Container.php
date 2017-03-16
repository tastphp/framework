<?php

namespace TastPHP\Framework\Container;

/**
 * Class Container
 * @package TastPHP\Framework\Container
 */
class Container extends \Pimple\Container
{
    public function buildReflector($class)
    {
        if (!class_exists($class)) {
            throw new \Exception("Class " . $class . " not found !");
        }

        $reflector = new \ReflectionClass($class);

        return $reflector;
    }

    /**
     * 设置response object
     *
     */
    public function setResponse($response)
    {
        $this['Response'] = $response;
    }

    /**
     * 获取设置的response object
     *
     *@return object
     */
    public function getResponse()
    {
        return $this['Response'];
    }

    /**
     * 设置request object
     *
     */
    public function setRequest($request)
    {
        $this['Request'] = $request;
    }

    /**
     * 获取设置的request object
     *
     *@return object
     */
    public function getRequest()
    {
        return $this['Request'];
    }
}
