<?php

namespace TastPHP\Framework\Jwt;

class JwtSigner extends \TastPHP\Framework\Service\ServiceMap
{
    public static function getMap()
    {
        return 'Signer';
    }
}