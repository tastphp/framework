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
        $response =  $app['twig']->render('errors/404.html');
        $app['eventDispatcher']->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $response));
    }
}
