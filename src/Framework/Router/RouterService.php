<?php

namespace TastPHP\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use TastPHP\Framework\Request\Request;

/**
 * Class Route
 * @package TastRouter
 * @author xujiajun [github.com/xujiajun]
 */
class Route
{
    private $url;

    private $methods = ['GET', 'POST', 'PUT', 'DELETE'];

    private $config = [];

    private $parameters = [];

    private $query = [];

    private $name = null;

    private $nameKey = 'routeName';//路由名的key值

    private $pattern = '\w+';

    private $middleware = null;

    private static $instance;

    public function __construct($url, $config)
    {
        self::$instance = $this;
        $this->url = $url;
        $this->config = $config;
        $this->methods = isset($config['methods']) ? $config['methods'] : array();
        $this->name = isset($config[$this->nameKey]) ? $config[$this->nameKey] : null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

    public function __unset($name)
    {
        unset($this->name);
    }

    public function getNameKey()
    {
        return $this->nameKey;
    }

    public function getDefaultPattern()
    {
        return $this->pattern;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        return $this->name = (String)$name;
    }

    public function setMiddleware($eventDispatcher, $event)
    {
        return $this->middleware = [$eventDispatcher, $event];
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameterByName($name)
    {
        return $this->parameters[$name];
    }

    /**
     * @param $class
     * @return \ReflectionClass
     * @throws \Exception
     */
    private function buildMoudle($class)
    {
        if (!class_exists($class)) {
            throw new \Exception("Class " . $class . " not found !");
        }

        $reflector = new \ReflectionClass($class);

        return $reflector;
    }

    public function dispatch($container)
    {
        $action = explode('::', $this->config['_controller']);

        if (count($action) != 2) {
            throw new \Exception('delimiter is wrong. ');
        }

        list($bundleName, $module) = explode('@', $action[0]);

        $namespaceDir = 'TastPHP';
        if ('test' === $container['env']) {
            $namespaceDir = 'TastPHP\\Tests';
        }
        $class = "{$namespaceDir}\\{$bundleName}Bundle\\Controller\\{$module}Controller";

        $reflector = $this->buildMoudle($class);
        $action = $action[1] . "Action";
        if (!$reflector->hasMethod($action)) {
            throw new \Exception("Class " . $class . " exist ,But the Action " . $action . " not found");
        }

        $symfonyRequest = $container['symfonyRequest'];

        if ($this->query) {
            $symfonyRequest->query = new ParameterBag($this->query);
        }

        if ($this->parameters) {
            $symfonyRequest->attributes = new ParameterBag($this->parameters);
        }

        if ($this->middleware) {
            list($eventDispatcher, $event) = $this->middleware;
            $event->setParameters($container);
            $eventDispatcher->dispatch($event::NAME, $event);
        }

        $instance = $reflector->newInstanceArgs(array($container));
        $method = $reflector->getmethod($action);

        $args = [];
        $parameters = $this->parameters;
        foreach ($method->getParameters() as $arg) {
            $paramName = $arg->getName();
            if (isset($parameters[$paramName])) {
                $args[$paramName] = $parameters[$paramName];
            }
            if (!empty($arg->getClass()) && $arg->getClass()->getName() == (ServerRequestInterface::class)) {
                $args[$paramName] = $container['Request'];
            }

            if (!empty($arg->getClass()) && $arg->getClass()->getName() == (Request::class)) {
                $args[$paramName] = $container['symfonyRequest'];
            }
        }

        return $method->invokeArgs($instance, $args);
    }
}

/**
 * Class Router
 * @package TastRouter
 * @author xujiajun [github.com/xujiajun]
 */
class RouterService
{
    /**
     * @var array|RouteCollection
     */
    private $routes = [];


    /**
     * @var array
     */
    private $namedroute = [];

    /**
     * @var null
     */
    private static $parameters = null;

    /**
     * @var null
     */
    private static $middleware = null;

    private static $webPath = __BASEDIR__ . '/web/';

    /**
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routes = $routeCollection;
    }

    /**
     * @return array|RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public static function setParameters($parameters)
    {
        self::$parameters = $parameters;
    }

    public static function setMiddleware($eventDispatcher, $event)
    {
        self::$middleware = [$eventDispatcher, $event];
    }

    /**
     * @return mixed
     */
    public function matchCurrentRequest()
    {
        $request = self::$parameters['symfonyRequest'];
        $requestMethod = $request->getMethod();
        $pathInfo = $request->getPathInfo();
        $webPath = self::$webPath;
        if (is_file($webPath . $pathInfo) && file_exists($webPath . $pathInfo)) {
            $content = file_get_contents(__BASEDIR__ . '/web/' . $pathInfo);
            echo $content;
            return;
        }

        return $this->match($pathInfo, $requestMethod);
    }

    /**
     * @param $requestUrl
     * @param string $requestMethod
     * @return mixed
     * @throws \Exception
     */
    public function match($requestUrl, $requestMethod = 'GET')
    {
        $isRegexp = false;

        $this->bind();

        foreach ($this->routes->all() as $route) {

            if (strpos($requestUrl, $route->getNamekey(), 0)) {
                throw new \Exception("Don't use route name key as part of your route");
            }

            if (!in_array($requestMethod, (array)$route->getMethods())) {
                continue;
            }

            $url = $route->getUrl();

            if (in_array($requestUrl, (array)$url)) {
                if (self::$middleware) {
                    list($eventDispatcher, $event) = self::$middleware;
                    $route->setMiddleware($eventDispatcher, $event);
                }
                self::$parameters['CurrentRoute'] = $route;
                return $route->dispatch(self::$parameters);
            }

            $isRegexp = $this->pregMatch($url, $requestUrl, $route);

            if (!in_array($requestUrl, (array)$url) && $isRegexp == false) {
                continue;
            }

            if (self::$middleware) {
                list($eventDispatcher, $event) = self::$middleware;
                $route->setMiddleware($eventDispatcher, $event);
            }
            self::$parameters['CurrentRoute'] = $route;
            return $route->dispatch(self::$parameters);
        }
        $request = self::$parameters['Request'];

        self::$parameters['eventDispatcher']->dispatch('app.notfound', new \TastPHP\Framework\Event\HttpEvent($request));
    }

    /**
     * @param $routeName
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function generate($routeName, array $parameters = [])
    {
        if (empty($this->namedroute[$routeName])) {
            throw new \Exception("No route named $routeName .");
        }

        $url = $this->namedroute[$routeName]->getUrl();
        preg_match_all('/({\w+})+?/', $url, $matches);
        $matches = $matches[0];

        if (!empty($matches)) {
            $matches = array_map(function ($matches) {
                return '/' . $matches . '/';
            }, $matches);

            return preg_replace($matches, $parameters, $url);
        }

        return $url;
    }

    /**
     * @param array $config
     * @return Router
     * @throws \Exception
     */
    public static function parseConfig(array $config)
    {
        $collection = new RouteCollection();
        foreach ($config as $name => $routeConfig) {
            if (empty($name)) {
                throw new \Exception('Check your config file! route name is missing');
            }

            //优先考虑routeName
            if (empty($routeConfig['parameters']['routeName'])) {
                $routeConfig['parameters']['routeName'] = $name;
            }

            $collection->attachRoute(new Route($routeConfig['pattern'], $routeConfig['parameters']));
        }

        return new RouterService($collection);
    }

    //bind name
    private function bind()
    {
        foreach ($this->routes->all() as $route) {
            $name = $route->getName();
            if (!empty($name)) {
                $this->namedroute[$name] = $route;
            }
        }
    }

    /**
     * @param $url
     * @param $requestUrl
     * @param $route
     * @return bool
     * @throws \Exception
     */
    private function pregMatch($url, $requestUrl, $route)
    {
        $replace = [];
        $search = [];
        $requireKeyNames = [];
        $configs = $route->getConfig();
        preg_match_all('/{(\w+)}/', $url, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $requireKey) {

                $pattern = $route->getDefaultPattern();

                if (!empty($configs[$requireKey])) {
                    $pattern = $configs[$requireKey];
                }

                $replace[] = "($pattern)";
                $search[] = '{' . $requireKey . '}';
                $requireKeyNames[] = $requireKey;
            }

            $pattern = str_replace('/', '\/', str_replace($search, $replace, $url));
            preg_match_all("/^$pattern$/", $requestUrl, $matcheParams);
            array_shift($matcheParams);

            if (empty($matcheParams) || empty($matcheParams[0])) {
                return false;
            }

            $parameters = [];
            $pos = 0;
            foreach ($matcheParams as $matcheParam) {
                if (empty($matcheParam)) {
                    throw new \Exception('check your parameter!');
                }
                $parameterName = $requireKeyNames[$pos];
                $parameters[$parameterName] = $matcheParam[0];
                $pos++;
            }

            $route->setParameters($parameters);
            return true;
        }
    }
}