<?php

namespace TastPHP\Framework\Adapter;

use TastPHP\Framework\Kernel;

class HttpAdapter
{
    protected function getPsr7Factory()
    {
        return $this->getKernel()['psr7Factory'];
    }

    protected function getHttpFactory()
    {
        return $this->getKernel()['httpFoundationFactory'];
    }

    protected function getKernel()
    {
        return Kernel::getInstance();
    }
}