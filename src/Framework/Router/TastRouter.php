<?php

namespace TastPHP\Framework\Router;

use TastPHP\Framework\Container\Container;
use TastPHP\Framework\Router\RouterService;
use TastPHP\Framework\Router\Route;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class TastRouter
 * @package TastPHP\Framework\Router
 */
class TastRouter
{
    public function register(Container $app)
    {   
        $isMobile = $app['isMobile'];

        $routeConfigAll = [];

        $routeCacheDir = __BASEDIR__ . "/var/cache/config/routes";
        if ($isMobile) {
            $routeCacheFile = $routeCacheDir . "/routeConfigMobile.php";
        } else {
            $routeCacheFile = $routeCacheDir . "/routeConfigAll.php"; 
        }

        if (file_exists($routeCacheFile) && (!$app['debug'])) {
            $routeConfigAll = require $routeCacheFile;
        } else {
            $routeConfigAll = $this->parseAllRoutes($app, $routeConfigAll);

            if (!$app['debug']) {
                $fs = new Filesystem();
                $fs->mkdir($routeCacheDir);
                $content = "<?php return " . var_export($routeConfigAll, true) . ";";

                file_put_contents($routeCacheFile, $content);
            }
        }

        $app['allRoutes'] = $routeConfigAll;
        $app['blankRoute'] = function () {
            return new Route('', []);
        };

        RouterService::setMiddleware($app['eventDispatcher'], $app['filterControllerEvent']);
        RouterService::setParameters($app);
    }

    private function parseAllRoutes($app, $routeConfigAll = [])
    {
        $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes.yml', $routeConfigAll, $app);

        if ($app['debug']) {
            $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes_test.yml', $routeConfigAll, $app);
        }

        return $routeConfigAll;
    }

    private function parseRoutesConfig($routesFile, $routeConfigAll, $app)
    {
        $array = [];
        $routesConfigs = \Yaml::parse(file_get_contents($routesFile));
        foreach ($routesConfigs as $key => $routeConfig) {
            //手机版只加载手机的route
            if (($app['isMobile'] && $key != 'mobile') || (!$app['isMobile'] && $key == 'mobile')) {
                continue;
            }
            $resource = ($routeConfig['resource']);
            if (is_file(__BASEDIR__ . "/src/" . $resource) && file_exists(__BASEDIR__ . "/src/" . $resource)) {
                $array = \Yaml::parse(file_get_contents(__BASEDIR__ . "/src/" . $resource));
            }
            $routeConfigAll = array_merge($routeConfigAll, $array);
        }

        return $routeConfigAll;
    }
}