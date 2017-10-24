<?php

namespace TastPHP\Framework\Queue;

use Pheanstalk\Pheanstalk as BeanstalkdCient;

class BeanstalkdService
{
    public function connect()
    {
        $app = \Kernel::getInstance();
        return $app->singleton('beanstalkd', function () use ($app) {
            $host = $app['beanstalkd.host'] ?? "127.0.0.1";
            $port = $app['beanstalkd.port'] ?? 11300;
            $connectTimeout = $app['beanstalkd.connectTimeout'] ?? 2;
            $connectPersistent = $app['beanstalkd.connectPersistent'] ?? false;
            $client = new BeanstalkdCient($host, $port, $connectTimeout, $connectPersistent);

            return $client;
        });
    }

    public function put($tube, $data, $priority = 1024, $delaytime = 0, $lifetime = 60)
    {
        $client = $this->connect();

        if ($client->getConnection()->isServiceListening() == false) {

            $app = \Kernel::getInstance();
            if (!empty($app['logger'])) {
                $app['logger']::error('[error: beanstalkd isConnected false]', []);
            }

            return;
        }
        return $client->useTube($tube)->put($data, $priority, $delaytime, $lifetime);
    }
}