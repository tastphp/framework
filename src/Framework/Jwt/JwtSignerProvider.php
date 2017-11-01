<?php

namespace TastPHP\Framework\Jwt;

use TastPHP\Framework\Service\ServiceProvider;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtSignerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('jwtSigner', function () {
            return new Sha256();
        });
    }
}