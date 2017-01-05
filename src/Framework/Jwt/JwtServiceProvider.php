<?php

namespace TastPHP\Framework\Jwt;

use TastPHP\Framework\Service\ServiceProvider;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtServiceProvider extends ServiceProvider
{
    public function register()
    {
        //jwt builder
        $this->app->singleton('jwtBuilder', function () {
            return new Builder();
        });

        //jwt parser
        $this->app->singleton('jwtParser', function () {
            return new Parser();
        });

        //Sha256
        $this->app->singleton('signer', function () {
            return new Sha256();
        });
    }
}