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
        'Config' => 'TastPHP\Framework\Config\ConfigServiceProvider',
        'Redis' => 'TastPHP\Framework\Cache\RedisServiceProvider',
        'Cache' => 'TastPHP\Framework\Cache\CacheServiceProvider',
        'FileCache' => 'TastPHP\Framework\Cache\FileCacheServiceProvider',
        'Logger' => 'TastPHP\Framework\Logger\LoggerServiceProvider',
        'EventDispatcher' => 'TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider',
        'Twig' => 'TastPHP\Framework\Twig\TwigServiceProvider',
        'Doctrine' => 'TastPHP\Framework\Doctrine\DoctrineServiceProvider',
        'CsrfToken' => 'TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider',
        'Jwt' => 'TastPHP\Framework\Jwt\JwtServiceProvider',
        'ListenerRegister' => 'TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider',
        'SwiftMailer' => 'TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider',
        'Queue' => 'TastPHP\Framework\Queue\QueueServiceProvider',
        'Router' => 'TastPHP\Framework\Router\RouterServiceProvider',
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

    /**
     * @param $alias string
     * @param $class string
     * @throws \Exception
     */
    public function replaceAlias($alias, $class)
    {
        $key = ucfirst($alias);
        if (!array_key_exists($key, $this->aliases)) {
            throw new KernelException("The alias {$key} is not exists");
        }

        $this->aliases[$key] = $class;
    }

    /**
     * @param $key string
     * @param $serviceProvider string
     * @throws \Exception
     */
    public function replaceServiceProvider($key, $serviceProvider)
    {
        $key = ucfirst($key);
        if (!array_key_exists($key, $this->serviceProviders)) {
            throw new KernelException("The serviceProvider {$key} is not exists");
        }

        $this->serviceProviders[$key] = $serviceProvider;
    }
}