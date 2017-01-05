<?php
namespace TastPHP\Framework\CsrfToken;

class CsrfToken extends \TastPHP\Framework\Service\ServiceMap
{
    public static function getMap()
    {
        return 'csrfToken';
    }
}