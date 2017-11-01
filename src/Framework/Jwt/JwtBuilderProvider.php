<?php

namespace TastPHP\Framework\Jwt;

use Lcobucci\JWT\Builder;
use TastPHP\Framework\Service\ServiceProvider;

class JwtBuilderProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('jwtBuilder', function () {
            return new Builder();
        });
    }
}