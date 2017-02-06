<?php

namespace TastPHP\Framework\Listener;

use Symfony\Component\HttpFoundation\Response;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\ExceptionEvent;

class ExceptionListener
{
    public function setMethod()
    {
        return 'onException';
    }

    public function onExceptionAction(ExceptionEvent $event)
    {
        if (app('env') == 'prod') {
            $response = app('twig')->render('errors/500.html');
        } else {
            $response = new Response($event->getTrace(), 500);
        }

        app('eventDispatcher')->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $response));
    }
}
