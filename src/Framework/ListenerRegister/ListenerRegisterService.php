<?php

namespace TastPHP\Framework\ListenerRegister;

use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\MailEvent;
use TastPHP\Framework\Listener\ExceptionListener;
use TastPHP\Framework\Listener\NotFoundListener;
use TastPHP\Framework\Listener\ResponseListener;
use TastPHP\Framework\Event\FilterControllerEvent;
use TastPHP\Framework\Listener\RequestListener;
use TastPHP\Framework\Listener\MiddlewareListener;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Listener\MailListener;

class ListenerRegisterService
{
    public function register(Container $app)
    {
        $app['filterControllerEvent'] = function ($app) {
            return new FilterControllerEvent($app);
        };

        $app['eventDispatcher']->addListener(AppEvent::REQUEST, [new RequestListener(), 'onRequestAction']);
        $app['eventDispatcher']->addListener($app['filterControllerEvent']::NAME, [new MiddlewareListener(), 'onMiddlewareAction']);
        $app['eventDispatcher']->addListener(AppEvent::RESPONSE, [new ResponseListener(), 'onResponseAction']);
        $app['eventDispatcher']->addListener(AppEvent::EXCEPTION, [new ExceptionListener(), 'onExceptionAction']);
        $app['eventDispatcher']->addListener(MailEvent::MAIlSEND, [new MailListener(), 'onSendMailAction']);
        $app['eventDispatcher']->addListener(AppEvent::NOTFOUND, [new NotFoundListener(), 'onNotFoundPageAction']);

        $listeners = $this->parseListenerConfig();
        foreach ($listeners as $listener) {
            if (!class_exists($listener['listener'])) {
                throw new \Exception("Class " . $listener['listener'] . " not found !");
            }

            $app['eventDispatcher']->addListener($listener['event'], [new $listener['listener'], $listener['callback']], $listener['priority']);
        }

    }

    private function parseListenerConfig($listenerConfigAll = [])
    {
        $array = [];
        $listenersConfigs = \Config::parse('listeners');
        foreach ($listenersConfigs as $listenerConfig) {
            $resource = ($listenerConfig['resource']);
            if (is_file(__BASEDIR__ . "/src/" . $resource) && file_exists(__BASEDIR__ . "/src/" . $resource)) {
                $array = \Yaml::parse(file_get_contents(__BASEDIR__ . "/src/" . $resource));
            }
            $listenerConfigAll = array_merge($listenerConfigAll, $array);
        }

        return $listenerConfigAll;
    }
}