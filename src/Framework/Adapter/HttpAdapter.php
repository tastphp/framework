<?php

namespace TastPHP\Framework\Adapter;

use TastPHP\Framework\Kernel;

class HttpAdapter
{
    protected static function getPsr7Factory()
    {
        return Kernel::getInstance()['psr7Factory'];
    }

    protected static function getHttpFactory()
    {
        return Kernel::getInstance()['httpFoundationFactory'];
    }
}