<?php

namespace TastPHP\Framework\Listener;

use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\AppEvent;

class NotFoundListener
{
    public function onNotFoundPageAction(HttpEvent $event)
    {
        $this->app = \Kernel::getInstance();
        $this->app['Request'] = $event->getRequest();
        $response =  $this->app['twig']->render('errors/404.html');
        $this->app['eventDispatcher']->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $response));
    }
}
