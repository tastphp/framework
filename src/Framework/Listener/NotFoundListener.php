<?php

namespace TastPHP\Framework\Listener;

use TastPHP\Framework\Event\HttpEvent;
use Symfony\Component\HttpFoundation\Response;

class NotFoundListener
{
    public function onNotFoundPageAction(HttpEvent $event)
    {
        $response =  app('twig')->render('errors/404.html');
        app()->setResponse(new Response($response, 404));
    }
}
