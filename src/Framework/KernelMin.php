<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Handler\AliasLoaderHandler;
use TastPHP\Framework\Container\Container;

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
        'TastPHP\Framework\Config\ConfigServiceProvider',
        'TastPHP\Framework\Cache\RedisServiceProvider',
        'TastPHP\Framework\Cache\CacheServiceProvider',
        'TastPHP\Framework\Cache\FileCacheServiceProvider',
        'TastPHP\Framework\Logger\LoggerServiceProvider',
        'TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider',
        'TastPHP\Framework\Doctrine\DoctrineServiceProvider',
        'TastPHP\Framework\DomainParser\DomainParserServiceProvider',
        'TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider',
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
}