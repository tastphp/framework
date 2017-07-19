<?php
namespace TastPHP\Framework\Traits;

use TastPHP\Framework\Event\MailEvent;
use TastPHP\Framework\Event\AppEvent;

trait KernelListeners
{
    /**
     * @var array
     */
    protected $listeners = [
        AppEvent::REQUEST => 'TastPHP\Framework\Listener\RequestListener@onRequestAction',
        AppEvent::MIDDLEWARE => 'TastPHP\Framework\Listener\MiddlewareListener@onMiddlewareAction',
        AppEvent::RESPONSE => 'TastPHP\Framework\Listener\ResponseListener@onResponseAction',
        AppEvent::EXCEPTION => 'TastPHP\Framework\Listener\ExceptionListener@onExceptionAction',
        MailEvent::MAIlSEND => 'TastPHP\Framework\Listener\MailListener@onSendMailAction',
        AppEvent::NOTFOUND => 'TastPHP\Framework\Listener\NotFoundListener@onNotFoundPageAction'
    ];
}