<?php

namespace TastPHP\Framework\Queue;

use Pheanstalk\Pheanstalk as BeanstalkdCient;

class BeanstalkdService
{
    public function connection()
    {	
    	$app = \Kernel::getInstance();
        return $app->singleton('beanstalkd', function () use ($app) {
            $client =  new BeanstalkdCient($app['beanstalkd.host'], 11300, 2);

            return $client;
        });
    }

    public function put($tube, $data, $priority = 1024, $delaytime = 0, $lifetime = 60)
	{	
		$client = $this->connection();

		if($client->getConnection()->isServiceListening() == false){

            \Logger::error('[error: beanstalkd isConnected false]', []);
            return;
        }
        
		return $client->useTube($tube)->put($data, $priority, $delaytime, $lifetime);
	}
}