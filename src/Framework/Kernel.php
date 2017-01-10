<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Handler\AliasLoaderHandler;
use TastPHP\Framework\Handler\ExceptionsHandler;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Event\AppEvent;

/**
 * Class Kernel
 * @package TastPHP\Framework
 */
class Kernel extends Container
{
    /**
     * @var Kernel
     */
    private static $instance;

    /**
     * @var array
     */
    protected $aliases = [
        'Kernel' => 'TastPHP\Framework\Kernel',
        'Config' => 'TastPHP\Framework\Config\Config',
        'Cache' => 'TastPHP\Framework\Cache\Cache',
        'FileCache' => 'TastPHP\Framework\Cache\FileCache',
        'ServiceProvider' => 'TastPHP\Framework\Service\ServiceProvider',
        'Logger' => 'TastPHP\Framework\Logger\Logger',
        'EventDispatcher' => 'TastPHP\Framework\EventDispatcher\EventDispatcher',
        'Router' => 'TastPHP\Framework\Router\Router',
        'Yaml' => 'Symfony\Component\Yaml\Yaml',
        'Twig' => 'TastPHP\Framework\Twig\Twig',
        'JwtBuilder' => 'TastPHP\Framework\Jwt\JwtBuilder',
        'JwtParser' => 'TastPHP\Framework\Jwt\JwtParser',
        'JwtSigner' => 'TastPHP\Framework\Jwt\JwtSigner',
        'Queue' => 'TastPHP\Framework\Queue\Queue',
    ];

    /**
     * @var array
     */
    protected $serviceProviders = [
        'TastPHP\Framework\Config\ConfigServiceProvider',
        'TastPHP\Framework\Cache\RedisServiceProvider',
        'TastPHP\Framework\Cache\CacheServiceProvider',
        'TastPHP\Framework\Cache\FileCacheServiceProvider',
        'TastPHP\Framework\Logger\LoggerServiceProvider',
        'TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider',
        'TastPHP\Framework\Twig\TwigServiceProvider',
        'TastPHP\Framework\Doctrine\DoctrineServiceProvider',
        'TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider',
        'TastPHP\Framework\Jwt\JwtServiceProvider',
        'TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider',
        'TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider',
        'TastPHP\Framework\Queue\QueueServiceProvider',
        'TastPHP\Framework\Router\RouterServiceProvider',
    ];

    /**
     * Kernel constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $start = microtime(true);
        $this['version'] = 'v1.0.0';
        $this['start_time'] = $start;
        self::$instance = $this;
        parent::__construct($values);

        $this->aliasLoader();

        $this->registerServices();

        $exception = new ExceptionsHandler();
        $exception->bootstrap($this);
    }

    /**
     * run app
     */
    public function run()
    {
        $this['eventDispatcher']->dispatch(AppEvent::RESPONSE, new \TastPHP\Framework\Event\HttpEvent(null, $this['router']->matchCurrentRequest()));
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
}