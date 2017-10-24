<?php

namespace TastPHP\Framework\Traits;

use TastPHP\Framework\Cache\CacheServiceProvider;
use TastPHP\Framework\Cache\FileCacheServiceProvider;
use TastPHP\Framework\Cache\RedisServiceProvider;
use TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider;
use TastPHP\Framework\Doctrine\DoctrineServiceProvider;
use TastPHP\Framework\Event\HttpEvent;
use TastPHP\Framework\Event\MailEvent;
use TastPHP\Framework\Handler\AliasLoaderHandler;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Jwt\JwtServiceProvider;
use TastPHP\Framework\Logger\LoggerServiceProvider;
use TastPHP\Framework\Queue\QueueServiceProvider;
use TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider;
use TastPHP\Framework\Twig\TwigServiceProvider;

/**
 * Class KernelTrait
 * @package TastPHP\Framework\Traits
 */
trait KernelTrait
{
    /**
     * run app
     */
    public function run()
    {
        $httpEvent = $this['eventDispatcher']->dispatch(AppEvent::RESPONSE, new HttpEvent(null, $this['router']->matchCurrentRequest(), $this));
        if (!empty($this['swoole'])) {
            return $httpEvent;
        }
    }

    /**
     * @param $name
     * @param null $callable
     * @return mixed
     */
    public function singleton($name, $callable = null)
    {
        if (!isset($this[$name]) && $callable) {
            $this[$name] = call_user_func($callable);
        }

        return $this[$name];
    }

    /**
     * @return Kernel
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * do the class alias
     */
    public function aliasLoader()
    {
        AliasLoaderHandler::getInstance($this->aliases)->register();
    }

    /**
     * register services
     */
    public function registerServices()
    {
        foreach ($this->serviceProviders as $provider) {
            if (empty($provider) || !class_exists($provider)) {
                continue;
            }
            $provider = new $provider(self::$instance);
            $provider->register();
        }
    }

    /**
     * @param array $aliases
     */
    protected function injectAliases(array $aliases)
    {
        $this->aliases = array_merge($this->aliases, $aliases);
    }

    /**
     * @param array $serviceProviders
     */
    protected function injectServiceProviders(array $serviceProviders)
    {
        $this->serviceProviders = array_merge($this->serviceProviders, $serviceProviders);
    }

    /**
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * @param $alias string
     * @param $class string
     * @throws \Exception
     */
    public function replaceAlias($alias, $class)
    {
        $key = ucfirst($alias);
        if (!isset($this->aliases[$key])) {
            throw new \InvalidArgumentException(sprintf('The alias "%s" is not defined.', $key));
        }

        $this->aliases[$key] = $class;
    }

    /**
     * @param $key string
     * @param $serviceProvider string
     */
    public function replaceServiceProvider($key, $serviceProvider)
    {
        $key = ucfirst($key);
        if (!isset($this->serviceProviders[$key])) {
            throw new \InvalidArgumentException(sprintf('The serviceProvider "%s" is not defined.', $key));
        }

        $this->serviceProviders[$key] = $serviceProvider;
    }

    /**
     * @param $key [example: AppEvent::REQUEST]
     * @param $listener [example: TastPHP\Framework\Listener\RequestListener]
     * @param string $action [example: onRequestAction]
     */
    public function replaceListener($key, $listener, $action = '')
    {
        if (!isset($this->listeners[$key])) {
            throw new \InvalidArgumentException(sprintf('The kernel listener "%s" is not defined.', $key));
        }

        if (empty($action)) {
            $this->listeners[$key] = $listener;
        }

        if (!empty($action)) {
            $this->listeners[$key] = $listener . '@' . $action;
        }
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getListeners($key = '')
    {
        if (!empty($key) && !empty($this->listeners[$key])) {
            return $this->listeners[$key];
        }

        return $this->listeners;
    }

    //ServiceProvider register

    protected function registerRedisService()
    {
        $this->replaceServiceProvider("Redis", RedisServiceProvider::class);
    }

    protected function registerCacheService()
    {
        $this->replaceServiceProvider("Cache", CacheServiceProvider::class);
    }

    protected function registerFileCacheService()
    {
        $this->replaceServiceProvider("FileCache", FileCacheServiceProvider::class);
    }

    protected function registerLoggerService()
    {
        $this->replaceServiceProvider("Logger", LoggerServiceProvider::class);
    }

    protected function registerTwigService()
    {
        $this->replaceServiceProvider("Twig", TwigServiceProvider::class);
    }

    protected function registerDoctrineService()
    {
        $this->replaceServiceProvider("Doctrine", DoctrineServiceProvider::class);
    }

    protected function registerCsrfTokenService()
    {
        $this->replaceServiceProvider("CsrfToken", CsrfTokenServiceProvider::class);
    }

    protected function registerJwtService()
    {
        $this->replaceServiceProvider("Jwt", JwtServiceProvider::class);
    }

    protected function registerSwiftMailerService()
    {
        $this->replaceServiceProvider("SwiftMailer", SwiftMailerServiceProvider::class);
    }

    protected function registerQueueService()
    {
        $this->replaceServiceProvider("Queue", QueueServiceProvider::class);
    }

    // kernel listener register

    /**
     * @param $listener
     * @param string $action
     */
    protected function registerRequestListener($listener, $action = "onRequestAction")
    {
        $this->replaceListener(AppEvent::REQUEST, $listener, $action);
    }

    protected function registerMiddlewareListener($listener, $action = "onMiddlewareAction")
    {
        $this->replaceListener(AppEvent::MIDDLEWARE, $listener, $action);
    }

    protected function registerResponseListener($listener, $action = "onResponseAction")
    {
        $this->replaceListener(AppEvent::RESPONSE, $listener, $action);
    }

    protected function registerExceptionListener($listener, $action = "onExceptionAction")
    {
        $this->replaceListener(AppEvent::EXCEPTION, $listener, $action);
    }

    protected function registerMailListener($listener, $action = "onSendMailAction")
    {
        $this->replaceListener(MailEvent::MAIlSEND, $listener, $action);
    }

    protected function registerNotFoundListener($listener, $action = "onNotFoundPageAction")
    {
        $this->replaceListener(AppEvent::NOTFOUND, $listener, $action);
    }
}