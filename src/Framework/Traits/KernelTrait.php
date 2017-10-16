<?php
namespace TastPHP\Framework\Traits;

use TastPHP\Framework\Handler\AliasLoaderHandler;
use TastPHP\Framework\Event\AppEvent;

/**
 * Class KernelTrait
 * @package TastPHP\Framework\Traits
 */
trait KernelTrait
{
    /**
     * run app
     */
    public function run()
    {
        $httpEvent = $this['eventDispatcher']->dispatch(AppEvent::RESPONSE, new \TastPHP\Framework\Event\HttpEvent(null, $this['router']->matchCurrentRequest(),$this));
        if (!empty($this['swoole'])) {
            return $httpEvent;
        }
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
            if (class_exists($provider)) {
                $provider = new $provider(self::$instance);
                $provider->register();
            }
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
        if (!isset($this->aliases[$key])) {
            throw new \InvalidArgumentException(sprintf('The alias "%s" is not defined.', $key));
        }

        $this->aliases[$key] = $class;
    }

    /**
     * @param $key string
     * @param $serviceProvider string
     */
    public function replaceServiceProvider($key, $serviceProvider)
    {
        $key = ucfirst($key);
        if (!isset($this->serviceProviders[$key])) {
            throw new \InvalidArgumentException(sprintf('The serviceProvider "%s" is not defined.', $key));
        }

        $this->serviceProviders[$key] = $serviceProvider;
    }

    /**
     * @param $key string
     * @param $listener string
     */
    public function replaceListener($key, $listener)
    {
        if (!isset($this->listeners[$key])) {
            throw new \InvalidArgumentException(sprintf('The kernel listener "%s" is not defined.', $key));
        }

        $this->listeners[$key] = $listener;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getListeners($key = '')
    {
        if (!empty($key) && !empty($this->listeners[$key])) {
            return $this->listeners[$key];
        }

        return $this->listeners;
    }
}