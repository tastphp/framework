<?php

namespace TastPHP\Framework\Logger;

use TastPHP\Framework\Service\ServiceMap;

class Logger extends ServiceMap
{
    public static function getMap()
    {
        return 'logger';
    }
}