<?php

namespace TastPHP\Framework\Queue;

use TastPHP\Framework\Service\ServiceMap;

class Queue extends ServiceMap
{
    public static function getMap()
    {
        return 'queue';
    }
}