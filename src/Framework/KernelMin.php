<?php

namespace TastPHP\Framework;

use Symfony\Component\Yaml\Yaml;
use TastPHP\Framework\Cache\Cache;
use TastPHP\Framework\Cache\CacheServiceProvider;
use TastPHP\Framework\Cache\FileCache;
use TastPHP\Framework\Cache\FileCacheServiceProvider;
use TastPHP\Framework\Cache\RedisServiceProvider;
use TastPHP\Framework\Config\Config;
use TastPHP\Framework\Config\ConfigServiceProvider;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider;
use TastPHP\Framework\Doctrine\DoctrineServiceProvider;
use TastPHP\Framework\EventDispatcher\EventDispatcher;
use TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider;
use TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider;
use TastPHP\Framework\Logger\Logger;
use TastPHP\Framework\Logger\LoggerServiceProvider;
use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Traits\KernelListeners;
use TastPHP\Framework\Traits\KernelTrait;
use TastPHP\Framework\Twig\TwigServiceProvider;

/**
 * Class KernelMin
 * @package TastPHP\Framework
 */
class KernelMin extends Container
{
    private static $instance;

    protected $aliases = [
        'Kernel' => KernelMin::class,
        'Config' => Config::class,
        'Cache' => Cache::class,
        'FileCache' => FileCache::class,
        'ServiceProvider' => ServiceProvider::class,
        'Logger' => Logger::class,
        'EventDispatcher' => EventDispatcher::class,
        'Yaml' => Yaml::class,
    ];

    protected $serviceProviders = [
        'Config' => ConfigServiceProvider::class,
        'Redis' => RedisServiceProvider::class,
        'Cache' => CacheServiceProvider::class,
        'FileCache' => FileCacheServiceProvider::class,
        'Logger' => LoggerServiceProvider::class,
        'EventDispatcher' => EventDispatcherServiceProvider::class,
        'Doctrine' => DoctrineServiceProvider::class,
        'ListenerRegister' => ListenerRegisterServiceProvider::class,
        'CsrfToken' => CsrfTokenServiceProvider::class,
        'Twig' => TwigServiceProvider::class
    ];

    use KernelListeners;

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

    use KernelTrait;

    public function setTimezone()
    {
        date_default_timezone_set($this['timezone']);
    }
}