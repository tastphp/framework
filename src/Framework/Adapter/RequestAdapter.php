<?php

namespace TastPHP\Framework\Adapter;

/**
 * Class RequestAdapter
 * @package TastPHP\Framework\Adapter
 */
class RequestAdapter extends HttpAdapter
{
    /**
     * @param Request $symfonyRequest
     * @return psrRequest
     */
    public static function convertPsr7Request(Request $symfonyRequest)
    {
        $psr7Factory = self::getPsr7Factory();

        return $psr7Factory->createRequest($symfonyRequest);
    }

    /**
     * @param $psrRequest
     * @return SymfonyRequest
     */
    public static function convertSymfonyRequest($psrRequest)
    {
        $httpFactory = self::getHttpFactory();

        return $httpFactory->createRequest($psrRequest);
    }
}