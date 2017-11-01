<?php

namespace TastPHP\Framework\Jwt;

use TastPHP\Framework\Service\ServiceProvider;
use Lcobucci\JWT\Parser;

class JwtParserProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('jwtParser', function () {
            return new Parser();
        });
    }
}