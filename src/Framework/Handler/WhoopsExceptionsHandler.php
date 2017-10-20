<?php

namespace TastPHP\Framework\Handler;

use TastPHP\Framework\Debug\DebugBar;
use TastPHP\Framework\Kernel;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Util\Misc;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\ExceptionEvent;
use TastPHP\Framework\Event\MailEvent;

class WhoopsExceptionsHandler
{
    public function register()
    {
        $container = Kernel::getInstance();

        //debug bar
        if ($container['debug']) {
            $debugBar = new DebugBar();
            $container['debugbar'] = $debugBar;
        }

        if (isset($container['display_errors']) && !$container['display_errors']) {
            ini_set('display_errors', 'Off');
        }

        $whoops = new \Whoops\Run;

        if (Misc::isCommandLine()) {
            $whoops->pushHandler(new PlainTextHandler());
        }
        if (!Misc::isCommandLine()) {
            $myhander = new PrettyPageHandler();
            $myhander->addDataTable('Tastphp Application', [
                'Version' => $container['version'],
                'Node' => $container['name'],
            ]);

            $whoops->pushHandler($myhander);
            $logger = new \Monolog\Logger('tastphp-logger');
            $logger->pushHandler(new \Monolog\Handler\StreamHandler(__BASEDIR__ . "/var/logs/error.log"));

            $whoops->pushHandler(function ($exception, $inspector, $run) use ($logger, $container) {

                $env = $container['env'];
                $node = $container['name'];

                $body = "App Env:【{$env}】, node:【{$node}】,error code:" . $exception->getCode() . " " . $exception->getMessage() . "trace:" . json_encode($exception->getTrace());

                $logger->addError($body);
                if (Misc::isLevelFatal($exception->getCode())) {

                    if ($container['env'] == 'prod') {
                        $container['eventDispatcher']->dispatch(AppEvent::EXCEPTION, new ExceptionEvent($exception, $container));
                    }

                    if (($container['swift.mail.enabled'] == 'on') && (!$container['debug'])) {
                        $container['eventDispatcher']->dispatch(MailEvent::MAIlSEND, new MailEvent($body));
                    }
                }

            });
        }

        $whoops->register();
    }
}