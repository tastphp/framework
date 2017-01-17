<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Handler\AliasLoaderHandler;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Event\AppEvent;

/**
 * Class KernelMin
 * @package TastPHP\Framework
 */
class KernelMin extends Container
{
    private static $instance;

    protected $aliases = [
        'Kernel' => 'TastPHP\Framework\KernelMin',
        'Config' => 'TastPHP\Framework\Config\Config',
        'Cache' => 'TastPHP\Framework\Cache\Cache',
        'FileCache' => 'TastPHP\Framework\Cache\FileCache',
        'ServiceProvider' => 'TastPHP\Framework\Service\ServiceProvider',
        'Logger' => 'TastPHP\Framework\Logger\Logger',
        'EventDispatcher' => 'TastPHP\Framework\EventDispatcher\EventDispatcher',
        'Yaml' => 'Symfony\Component\Yaml\Yaml',
    ];

    protected $serviceProviders = [
        'Config' => 'TastPHP\Framework\Config\ConfigServiceProvider',
        'Redis' => 'TastPHP\Framework\Cache\RedisServiceProvider',
        'Cache' => 'TastPHP\Framework\Cache\CacheServiceProvider',
        'FileCache' => 'TastPHP\Framework\Cache\FileCacheServiceProvider',
        'Logger' => 'TastPHP\Framework\Logger\LoggerServiceProvider',
        'EventDispatcher' => 'TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider',
        'Doctrine' => 'TastPHP\Framework\Doctrine\DoctrineServiceProvider',
        'ListenerRegister' => 'TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider',
        'CsrfToken' => 'TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider',
        'Twig' => 'TastPHP\Framework\Twig\TwigServiceProvider',
    ];


    public function __construct(array $values = [])
    {
        $start = microtime(true);
        $this['start_time'] = $start;
        self::$instance = $this;
        parent::__construct($values);

        $this->aliasLoader();
        $this->registerServices();
        $this->setTimezone();
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
     * @return KernelMin
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

    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
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

    public function setTimezone()
    {
        date_default_timezone_set($this['timezone']);
    }

    /**
     * run app
     */
    public function run()
    {
        $this['eventDispatcher']->dispatch(AppEvent::RESPONSE, new \TastPHP\Framework\Event\HttpEvent(null, $this['router']->matchCurrentRequest()));
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