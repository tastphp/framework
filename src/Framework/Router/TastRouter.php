<?php

namespace TastPHP\Framework\Router;

use TastPHP\Framework\Config\YamlService;
use TastPHP\Framework\Container\Container;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class TastRouter
 * @package TastPHP\Framework\Router
 */
class TastRouter
{
    public function register(Container $app)
    {
        $routeConfigAll = [];

        $routeCacheDir = __BASEDIR__ . "/var/cache/config/routes";
        $routeCacheFile = $routeCacheDir . "/routeConfigAll.php";

        if (file_exists($routeCacheFile) && (!$app['debug'])) {
            $routeConfigAll = require $routeCacheFile;
        } else {
            $routeConfigAll = $this->parseAllRoutes($app,$routeConfigAll);

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

        RouterService::setMiddleware($app['eventDispatcher'], $app['middlewareEvent']);
        RouterService::setParameters($app);
    }

    private function parseAllRoutes($app,$routeConfigAll = [])
    {
        $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes.yml', $routeConfigAll);

        if ($app['debug']) {
            $routeConfigAll = $this->parseRoutesConfig(__BASEDIR__ . '/config/routes_test.yml', $routeConfigAll);
        }

        return $routeConfigAll;
    }

    private function parseRoutesConfig($routesFile, $routeConfigAll)
    {
        $array = [];
        $routesConfigs = YamlService::parse(file_get_contents($routesFile));
        foreach ($routesConfigs as $routeConfig) {
            $resource = ($routeConfig['resource']);
            if (is_file(__BASEDIR__ . "/src/" . $resource) && file_exists(__BASEDIR__ . "/src/" . $resource)) {
                $array = YamlService::parse(file_get_contents(__BASEDIR__ . "/src/" . $resource));
            }
            $routeConfigAll = array_merge($routeConfigAll, $array);
        }

        return $routeConfigAll;
    }
}