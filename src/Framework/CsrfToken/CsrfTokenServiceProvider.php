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
        $csrfSecret = $this->app['csrf.secret'] ?? 'tastphp.token';
        $csrfTtl = $this->app['csrf.ttl'] ?? 1440;

        $this->app->singleton('csrfToken', function () use ($csrfSecret,$csrfTtl) {
            return new CsrfTokenService($csrfSecret, $csrfTtl);
        });
    }
}