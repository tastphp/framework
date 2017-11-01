<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Cache\CacheServiceProvider;
use TastPHP\Framework\Cache\FileCacheServiceProvider;
use TastPHP\Framework\Cache\RedisServiceProvider;
use TastPHP\Framework\Config\Config;
use TastPHP\Framework\Config\ConfigServiceProvider;
use TastPHP\Framework\Container\PimpleContainerProvider;
use TastPHP\Framework\CsrfToken\CsrfTokenServiceProvider;
use TastPHP\Framework\Doctrine\DoctrineServiceProvider;
use TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider;
use TastPHP\Framework\ExceptionHandler\ExceptionHandlerServiceProvider;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Jwt\JwtBuilderProvider;
use TastPHP\Framework\Jwt\JwtParserProvider;
use TastPHP\Framework\Jwt\JwtSignerProvider;
use TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider;
use TastPHP\Framework\Http\RequestServiceProvider;
use TastPHP\Framework\Logger\LoggerServiceProvider;
use TastPHP\Framework\Queue\QueueServiceProvider;
use TastPHP\Framework\Router\RouterServiceProvider;
use TastPHP\Framework\Service\ServiceKernelProvider;
use TastPHP\Framework\SwiftMailer\SwiftMailerServiceProvider;
use TastPHP\Framework\Traits\KernelListeners;
use TastPHP\Framework\Traits\KernelTrait;
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
    ];

    /**
     * @var serviceProviders array
     */
    protected $serviceProviders = [
        'Config' => [ConfigServiceProvider::class, true],
        'Request' => [RequestServiceProvider::class, true],
        'Redis' => [RedisServiceProvider::class, false],
        'Cache' => [CacheServiceProvider::class, false],
        'FileCache' => [FileCacheServiceProvider::class, false],
        'Logger' => [LoggerServiceProvider::class, false],
        'EventDispatcher' => [EventDispatcherServiceProvider::class, true],
        'Twig' => [TwigServiceProvider::class, false],
        'Dbs' => [DoctrineServiceProvider::class, false],
        'ServiceKernel' => [ServiceKernelProvider::class, false],
        'CsrfToken' => [CsrfTokenServiceProvider::class, false],
        'JwtBuilder' => [JwtBuilderProvider::class, false],
        'JwtParser' => [JwtParserProvider::class, false],
        'JwtSigner' => [JwtSignerProvider::class, false],
        'ListenerRegister' => [ListenerRegisterServiceProvider::class, true],
        'SwiftMailer' => [SwiftMailerServiceProvider::class, false],
        'Queue' => [QueueServiceProvider::class, false],
        'Router' => [RouterServiceProvider::class, true],
        'ExceptionHandler' => [ExceptionHandlerServiceProvider::class, true],
        'Container' => [PimpleContainerProvider::class, false]
    ];

    /**
     * @var KernelListeners array
     */
    use KernelListeners;

    /**
     * Kernel constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $start = microtime(true);
        $this['version'] = 'v2.0.0';
        $this['start_time'] = $start;
        self::$instance = $this;
        parent::__construct($values);
        $this->aliasLoader();
        $this->registerServices();
    }

    use KernelTrait;
}