<?php

namespace TastPHP\Framework\Doctrine;

use TastPHP\Framework\Container\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;

class DoctrineService
{
    public function register(Container $app)
    {
        $app['db.default_options'] = array(
            'driver' => 'pdo_mysql',
            'dbname' => 'test',
            'host' => 'localhost',
            'user' => 'root',
            'password' => 123,
        );

        $app['dbs.options.initializer'] = $app->protect(function () use ($app) {
            static $initialized = false;

            if ($initialized) {
                return;
            }

            $initialized = true;

            if (!isset($app['dbs.options'])) {
                $app['dbs.options'] = array('default' => isset($app['db.options']) ? $app['db.options'] : array());
            }

            $tmp = $app['dbs.options'];
            foreach ($tmp as $name => &$options) {
                $options = array_replace($app['db.default_options'], $options);

                if (!isset($app['dbs.default'])) {
                    $app['dbs.default'] = $name;
                }
            }
            $app['dbs.options'] = $tmp;
        });
        $app['dbs'] = $app->factory(function ($app) {
            $app['dbs.options.initializer']();

            $dbs = new Container();
            if ($app['debug'] && !empty($app['debugbar'])) {
                $debugStack = new \Doctrine\DBAL\Logging\DebugStack();
                $app->singleton('debugbar')->addCollector(new \DebugBar\Bridge\DoctrineCollector($debugStack));
            }

            foreach ($app['dbs.options'] as $name => $options) {
                if ($app['dbs.default'] === $name) {
                    // we use shortcuts here in case the default has been overridden
                    $config = $app['db.config'];
                    $manager = $app['db.event_manager'];
                } else {
                    $config = $app['dbs.config'][$name];
                    $manager = $app['dbs.event_manager'][$name];
                }
                $dbs[$name] = DriverManager::getConnection($options, $config, $manager);

                //debug bar
                if ($app['debug'] && !empty($debugStack)) {
                    $dbs[$name]->getConfiguration()->setSQLLogger($debugStack);
                }
            }

            return $dbs;
        });

        $app['dbs.config'] = $app->factory(function ($app) {
            $app['dbs.options.initializer']();

            $configs = new Container();
            foreach ($app['dbs.options'] as $name => $options) {
                $configs[$name] = new Configuration();
            }

            return $configs;
        });

        $app['dbs.name'] = $app->factory(function ($app) {
            $names = [];
            foreach ($app['dbs.options'] as $name => $options) {
                $names[] = $name;
            }
            return $names;
        });

        $app['dbs.event_manager'] = $app->factory(function ($app) {
            $app['dbs.options.initializer']();

            $managers = new Container();
            foreach ($app['dbs.options'] as $name => $options) {
                $managers[$name] = new EventManager();
            }

            return $managers;
        });

        // shortcuts for the "first" DB
        $app['db'] = $app->factory(function ($app) {
            $dbs = $app['dbs'];

            return $dbs[$app['dbs.default']];
        });

        $app['db.config'] = $app->factory(function ($app) {
            $dbs = $app['dbs.config'];

            return $dbs[$app['dbs.default']];
        });

        $app['db.event_manager'] = $app->factory(function ($app) {
            $dbs = $app['dbs.event_manager'];

            return $dbs[$app['dbs.default']];
        });
    }
}