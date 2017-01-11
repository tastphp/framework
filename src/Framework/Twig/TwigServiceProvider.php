<?php

namespace TastPHP\Framework\Twig;

use TastPHP\Framework\Service\ServiceProvider;

class TwigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('twigService', function () {
            return new TwigService();
        });

        $this->app->singleton('twigService')->register($this->app);
    }
}