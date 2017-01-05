<?php

namespace TastPHP\Framework\Twig;

use TastPHP\Framework\Container\Container;

class TwigService extends Twig
{
    public function register(Container $app)
    {
        $app->singleton('twig', function () use ($app) {

            $loader = new \Twig_Loader_Filesystem([
                __BASEDIR__ . '/web/views/',
                __BASEDIR__ . '/web/views/frontBundle',
            ]);

            $env = array(
                'charset' => 'utf-8',
                'debug' => $app['debug'],
                'cache' => __BASEDIR__ . '/var/cache/twig',
                'strict_variables' => $app['debug'],
            );

            $twig = new \Twig_Environment($loader, isset($env) ? $env : array());
            if(class_exists('\\TastPHP\\FrontBundle\\Twig\\Extension\\WebExtension')) {
                $twig->addExtension(new \TastPHP\FrontBundle\Twig\Extension\WebExtension($app));
            }
            return $twig;
        });

    }
}