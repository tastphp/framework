<?php

namespace TastPHP\Framework\Listener;

use Symfony\Component\HttpFoundation\Response;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Request;

class ExceptionListener
{
    public function setMethod()
    {
        return 'onException';
    }

    public function onExceptionAction(ExceptionEvent $event)
    {
        $app = \Kernel::getInstance();

        $response = "500";

        if ($app['env'] == 'prod') {
            $app['Request'] = Request::createFromGlobals();

        }

        if($app['env'] != 'prod') {
            $response = new Response($event->getTrace(), 500);
        }

        $event->getContainer()->singleton('eventDispatcher')->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $response));
    }
}
