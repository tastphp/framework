<?php
namespace TastPHP\Framework\EventDispatcher;

class EventDispatcher extends \TastPHP\Framework\Service\ServiceMap
{
    public static function getMap()
    {
        return 'eventDispatcher';
    }
}