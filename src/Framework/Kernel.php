<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Handler\ExceptionsHandler;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Traits\KernelListeners;
use TastPHP\Framework\Traits\KernelTrait;

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

    use KernelListeners;

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

    use KernelTrait;
}