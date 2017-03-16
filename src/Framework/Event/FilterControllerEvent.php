<?php

namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event;

class FilterControllerEvent extends Event
{
    const NAME = 'app.middleware';

    private static $container = null;

    public function __construct($container)
    {
        self::$container = $container;
    }

    public function setParameters($container)
    {
        self::$container = $container;
    }

    public function getParameters()
    {
        return self::$container;
    }
}