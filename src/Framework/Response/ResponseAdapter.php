<?php

namespace TastPHP\Framework\Response;

/**
 * Class ResponseAdapter
 * @package TastPHP\Framework\Response
 */
class ResponseAdapter
{
    /**
     * @param Response $symfonyResponse
     * @return psrResponse
     */
    public static function convertPsr7Response(Response $symfonyResponse)
    {
        $app = \Kernel::getInstance();
        $psr7Factory = $app['psr7Factory'];

        return $psr7Factory->createResponse($symfonyResponse);
    }

    /**
     * @param $psrResponse
     * @return SymfonyResponse
     */
    public static function convertSymfonyResponse($psrResponse)
    {
        $app = \Kernel::getInstance();
        $httpFoundationFactory = $app['httpFoundationFactory'];

        return $httpFoundationFactory->createResponse($psrResponse);
    }
}