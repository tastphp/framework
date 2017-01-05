<?php

namespace TastPHP\Framework\Router;

/**
 * Class RouteCollection
 * @package TastPHP\Framework\Router
 */
class RouteCollection extends \SplObjectStorage
{
    /**
     * @param Route $route
     */
    public function attachRoute(Route $route)
    {
        parent::attach($route);
    }

    /**
     * @return array
     */
    public function all()
    {
        $temp = [];
        foreach ($this as $route) {
            $temp[] = $route;
        }

        return $temp;
    }
}