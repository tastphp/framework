<?php

namespace TastPHP\Framework\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseListener
{
    public function onResponseAction(Event $event)
    {
        $response = $event->getResponse();

        if (is_string($response)) {
            echo $response;
            return;
        }

        if ($response instanceof Response
            || $response instanceof RedirectResponse
            || $response instanceof JsonResponse
        ) {
            $response->send();
        }

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        exit;
    }
}