<?php

namespace TastPHP\Framework\Router;

use TastPHP\Framework\Config\ConfigService;
use TastPHP\Framework\Config\YamlService;
use TastPHP\Framework\Service\ServiceProvider;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\HttpEvent;

class RouterServiceProvider extends ServiceProvider
{
    protected $enabledCache = false;

    public function setEnabledCache()
    {
        $this->enabledCache = true;
    }

    public function register()
    {
        $request = $this->app['symfonyRequest'];

        if (!$this->app->runningInConsole()) {
            $request = $this->app['Request'];
        }

        $this->app->singleton('eventDispatcher')->dispatch(AppEvent::REQUEST, new HttpEvent($request));

        $this->registerRoutes($this->app);

        $this->app['router'] = RouterService::parseConfig($this->app['allRoutes']);
    }

    public function registerRoutes($app)
    {
        $routeConfigAll = [];

        $routeCacheDir = __BASEDIR__ . "/var/cache/config/routes";
        $routeCacheFile = $routeCacheDir . "/routeConfigAll.php";

        if (file_exists($routeCacheFile) && (!$app['debug'])) {
            $routeConfigAll = require $routeCacheFile;
        } else {
            $routeConfigAll = $this->parseAllRoutes($app, $routeConfigAll);
            if (!$app['debug'] || $this->enabledCache) {
                ConfigService::createCache($routeConfigAll, $routeCacheFile, $routeCacheDir);
            }
        }

        $app['allRoutes'] = $routeConfigAll;
        $app['blankRoute'] = function () {
            return new Route('', []);
        };

        RouterService::setMiddleware($app['eventDispatcher'], $app['middlewareEvent']);
        RouterService::setParameters($app);
    }

    private function parseAllRoutes($app, $routeConfigAll = [])
    {
        $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes.yml', $routeConfigAll);

        if ($app['debug']) {
            $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes_test.yml', $routeConfigAll);
        }

        return $routeConfigAll;
    }

    private function parseRoutesConfig($routesFile, $routeConfigAll)
    {
        $routesConfigs = YamlService::parse(file_get_contents($routesFile));
        foreach ($routesConfigs as $routeConfig) {
            $resources = ($routeConfig['resource']);

            if (is_array($resources)) {
                foreach ($resources as $resource) {
                    $routeConfigAll = $this->parseRouteResource($resource,$routeConfigAll);
                }
            }

            if (!is_array($resources)) {
                $routeConfigAll = $this->parseRouteResource($resources,$routeConfigAll);
            }
        }

        return $routeConfigAll;
    }

    private function parseRouteResource($resource,$routeConfigAll)
    {
        if (is_file(__BASEDIR__ . "/src/" . $resource) && file_exists(__BASEDIR__ . "/src/" . $resource)) {
            $array = YamlService::parse(file_get_contents(__BASEDIR__ . "/src/" . $resource));
        }
        if (!empty($array)) {
            $routeConfigAll = array_merge($routeConfigAll, $array);
        }

        return $routeConfigAll;
    }
}