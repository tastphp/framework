<?php

namespace TastPHP\Framework\Twig;

use TastPHP\Framework\Container\Container;

class TwigService extends Twig
{
    private $loaderBaseDir = [__BASEDIR__ . '/web/views/'];

    public function register(Container $app)
    {
        $app->singleton('twig', function () use ($app) {

            $loader = new \Twig_Loader_Filesystem($this->loaderBaseDir);

            $env = array(
                'charset' => 'utf-8',
                'debug' => $app['debug'],
                'cache' => __BASEDIR__ . '/var/cache/twig',
                'strict_variables' => $app['debug'],
            );

            $twig = new \Twig_Environment($loader, isset($env) ? $env : array());
            dump(class_exists('\\TastPHP\\FrontBundle\\Twig\\Extension\\WebExtension'));exit();
            if(class_exists('\\TastPHP\\FrontBundle\\Twig\\Extension\\WebExtension')) {
            $twig->addExtension(new \TastPHP\FrontBundle\Twig\Extension\WebExtension($app));
            }
            return $twig;
        });

    }

    protected function setLoaderBaseDir(array $loaderBaseDir)
    {
        $this->loaderBaseDir = $loaderBaseDir;
    }
}