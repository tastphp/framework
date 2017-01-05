<?php

namespace TastPHP\Framework\Queue;

use ServiceProvider;
use TastPHP\Framework\Queue\BeanstalkdService;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return object
     */
    public function register()
    {
        $this->app->singleton('queue', function () {
            return new BeanstalkdService();
        });
    }
}
