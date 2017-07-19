<?php

namespace TastPHP\Framework\Adapter;

/**
 * Class ResponseAdapter
 * @package TastPHP\Framework\Adapter
 */
class ResponseAdapter extends HttpAdapter
{
    /**
     * @param Response $symfonyResponse
     * @return psrResponse
     */
    public static function convertPsr7Response(Response $symfonyResponse)
    {
        $psr7Factory = self::getPsr7Factory();

        return $psr7Factory->createResponse($symfonyResponse);
    }

    /**
     * @param $psrResponse
     * @return SymfonyResponse
     */
    public static function convertSymfonyResponse($psrResponse)
    {
        $httpFactory = self::getHttpFactory();

        return $httpFactory->createResponse($psrResponse);
    }
}