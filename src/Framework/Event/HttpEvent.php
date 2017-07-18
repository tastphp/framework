<?php

namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event;

final class HttpEvent extends Event
{
    protected $request;

    protected $response;

    public function __construct($request = null, $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
