<?php

namespace TastPHP\Framework;

use TastPHP\Framework\Config\Config;
use TastPHP\Framework\Config\ConfigServiceProvider;
use TastPHP\Framework\EventDispatcher\EventDispatcherServiceProvider;
use TastPHP\Framework\ExceptionHandler\ExceptionHandlerServiceProvider;
use TastPHP\Framework\Container\Container;
use TastPHP\Framework\ListenerRegister\ListenerRegisterServiceProvider;
use TastPHP\Framework\Http\RequestServiceProvider;
use TastPHP\Framework\Router\RouterServiceProvider;
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
        'Kernel' => Kernel::class,
        'Config' => Config::class,
    ];

    /**
     * @var serviceProviders array
     */
    protected $serviceProviders = [
        'Config' => ConfigServiceProvider::class,
        'Request' => RequestServiceProvider::class,
        'Redis' => "",
        'Cache' => "",
        'FileCache' => "",
        'Logger' => "",
        'EventDispatcher' => EventDispatcherServiceProvider::class,
        'Twig' => "",
        'Doctrine' => "",
        'CsrfToken' => "",
        'Jwt' => "",
        'ListenerRegister' => ListenerRegisterServiceProvider::class,
        'SwiftMailer' => "",
        'Queue' => "",
        'Router' => RouterServiceProvider::class,
        'ExceptionHandler' => ExceptionHandlerServiceProvider::class,
        'Container' => ""
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
        $this['version'] = 'v1.7.0';
        $this['start_time'] = $start;
        self::$instance = $this;
        parent::__construct($values);
        $this->aliasLoader();
        $this->registerServices();
    }

    use KernelTrait;
}