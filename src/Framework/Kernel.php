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
use TastPHP\Framework\Container\PimpleContainerProvider;
use TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider;
use TastPHP\Framework\Doctrine\DoctrineServiceProvider;
use TastPHP\Framework\EventDispatcher\EventDispatcher;
use TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider;
use TastPHP\Framework\ExceptionHandler\ExceptionHandlerServiceProvider;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Jwt\JwtBuilder;
use TastPHP\Framework\Jwt\JwtParser;
use TastPHP\Framework\Jwt\JwtServiceProvider;
use TastPHP\Framework\Jwt\JwtSigner;
use TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider;
use TastPHP\Framework\Logger\Logger;
use TastPHP\Framework\Logger\LoggerServiceProvider;
use TastPHP\Framework\Queue\Queue;
use TastPHP\Framework\Queue\QueueServiceProvider;
use TastPHP\Framework\Http\RequestServiceProvider;
use TastPHP\Framework\Router\Router;
use TastPHP\Framework\Router\RouterServiceProvider;
use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider;
use TastPHP\Framework\Traits\KernelListeners;
use TastPHP\Framework\Traits\KernelTrait;
use TastPHP\Framework\Twig\Twig;
use TastPHP\Framework\Twig\TwigServiceProvider;

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
        'Kernel' => Kernel::class,
        'Config' => Config::class,
        'Cache' => Cache::class,
        'FileCache' => FileCache::class,
        'ServiceProvider' => ServiceProvider::class,
        'Logger' => Logger::class,
        'EventDispatcher' => EventDispatcher::class,
        'Router' => Router::class,
        'Yaml' => Yaml::class,
        'Twig' => Twig::class,
        'JwtBuilder' => JwtBuilder::class,
        'JwtParser' => JwtParser::class,
        'JwtSigner' => JwtSigner::class,
        'Queue' => Queue::class,
    ];

    /**
     * @var array
     */
    protected $serviceProviders = [
        'Config' => ConfigServiceProvider::class,
        'Request' => RequestServiceProvider::class,
        'Redis' => RedisServiceProvider::class,
        'Cache' => CacheServiceProvider::class,
        'FileCache' => FileCacheServiceProvider::class,
        'Logger' => LoggerServiceProvider::class,
        'EventDispatcher' => EventDispatcherServiceProvider::class,
        'Twig' => TwigServiceProvider::class,
        'Doctrine' => DoctrineServiceProvider::class,
        'CsrfToken' => CsrfTokenServiceProvider::class,
        'Jwt' => JwtServiceProvider::class,
        'ListenerRegister' => ListenerRegisterServiceProvider::class,
        'SwiftMailer' => SwiftMailerServiceProvider::class,
        'Queue' => QueueServiceProvider::class,
        'Router' => RouterServiceProvider::class,
        'ExceptionHandler' => ExceptionHandlerServiceProvider::class,
        'Container' => PimpleContainerProvider::class
    ];

    use KernelListeners;

    /**
     * Kernel constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $start = microtime(true);
        $this['version'] = 'v1.6.0';
        $this['start_time'] = $start;
        self::$instance = $this;
        parent::__construct($values);
        $this->aliasLoader();
        $this->registerServices();
    }

    use KernelTrait;
}