<?php

namespace TastPHP\Framework\Listener;

use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\AppEvent;

class NotFoundListener
{
    public function onNotFoundPageAction(HttpEvent $event)
    {
        $app = \Kernel::getInstance();
        $app['Request'] = $event->getRequest();
        $response =  "404";
        $app['eventDispatcher']->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $response));
    }
}
