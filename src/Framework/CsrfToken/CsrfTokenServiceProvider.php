<?php

namespace TastPHP\Framework\CsrfToken;

use TastPHP\Framework\Service\ServiceProvider;

/**
 * Class CsrfTokenServiceProvider
 * @package TastPHP\Framework\CsrfToken
 */
class CsrfTokenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('csrfToken', function () {
            return new CsrfTokenService($this->app['csrf.secret'], $this->app['csrf.ttl']);
        });
    }
}