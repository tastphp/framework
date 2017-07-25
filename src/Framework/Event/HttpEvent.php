<?php

namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event;

final class HttpEvent extends Event
{
    protected $request;

    protected $response;

    protected $parameters;

    public function __construct($request = null, $response = null, $parameters = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->parameters = $parameters;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}