<?php
namespace TastPHP\Framework;

trait KernelTrait
{
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

    /**
     * @param $key
     * @param $listener
     * @throws KernelException
     */
    public function replaceListener($key, $listener)
    {
        if (!array_key_exists($key, $this->listeners)) {
            throw new KernelException("The kernel listener {$key} is not exists");
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