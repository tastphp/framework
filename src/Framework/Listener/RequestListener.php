<?php

namespace TastPHP\Framework\Listener;

use TastPHP\Framework\Event\HttpEvent;
use Symfony\Component\HttpFoundation\Response;
use TastPHP\Framework\Event\AppEvent;

class RequestListener
{
    public function onRequestAction(HttpEvent $event)
    {	
    	$app = \Kernel::getInstance();

        //TODO
        if (!empty($app['service_kernel'])) {
            $ips = $app['service_kernel']->registerService('Setting.SettingService')->get('ip_blacklist');
        }
        $ips = empty($ips) ? [] : $ips;
        $request = $event->getRequest();
        $ip = $request->getClientIp();

        if (in_array($ip, $ips)) {
        	$response = new Response('Access Forbidden', 403
            );
            $app->singleton('eventDispatcher')->dispatch(AppEvent::RESPONSE, new \TastPHP\Framework\Event\HttpEvent(null, $response));
        }
    }
}