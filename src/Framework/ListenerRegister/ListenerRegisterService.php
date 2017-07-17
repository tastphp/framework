<?php

namespace TastPHP\Framework\ListenerRegister;

use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Event\FilterControllerEvent;

class ListenerRegisterService
{
    public function register(Container $app)
    {
        $app['filterControllerEvent'] = function ($app) {
            return new FilterControllerEvent($app);
        };

        $this->registerKernelListeners($app);

        $listeners = $this->parseListenerConfig();

        foreach ($listeners as $listener) {
            if (empty($listener['listener']) || empty($listener['callback']) || empty($listener['event'])) {
                throw new \InvalidArgumentException("property is not defined in listener config.");
            }

            if (!class_exists($listener['listener'])) {
                throw new \Exception("Class " . $listener['listener'] . " not found !");
            }

            if (!is_callable([$listener['listener'], $listener['callback']])) {
                throw new \BadFunctionCallException('Function ' . $listener['callback'] . ' is not callable');
            }

            if (empty($listener['priority'])) {
                $listener['priority'] = 0;
            }
            $app['eventDispatcher']->addListener($listener['event'], [new $listener['listener'], $listener['callback']], $listener['priority']);
        }
    }

    private function registerKernelListeners($app)
    {
        $listeners = \Kernel::getInstance()->getListeners();
        foreach ($listeners as $eventName => $listener) {
            list($listener, $callback) = explode('@', $listener);
            if (!class_exists($listener)) {
                continue;
            }
            $app['eventDispatcher']->addListener($eventName, [new $listener(), $callback]);
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

            if (is_array($array)) {
                $listenerConfigAll = array_merge($listenerConfigAll, $array);
            }
        }

        return $listenerConfigAll;
    }
}