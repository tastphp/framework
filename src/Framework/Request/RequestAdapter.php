<?php

namespace TastPHP\Framework\Request;

/**
 * Class RequestAdapter
 * @package TastPHP\Framework\Request
 */
class RequestAdapter
{
    /**
     * @param Request $symfonyRequest
     * @return mixed
     */
    public static function convertPsr7Request(Request $symfonyRequest)
    {
        $app = \Kernel::getInstance();
        $psr7Factory = $app['psr7Factory'];

        return $psr7Factory->createRequest($symfonyRequest);
    }

    /**
     * @param $psrRequest
     * @return mixed
     */
    public static function convertSymfonyRequest($psrRequest)
    {
        $app = \Kernel::getInstance();
        $httpFoundationFactory = $app['httpFoundationFactory'];;
        return $httpFoundationFactory->createRequest($psrRequest);
    }
}