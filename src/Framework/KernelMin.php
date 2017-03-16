<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Traits\KernelListeners;
use TastPHP\Framework\Traits\KernelTrait;

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