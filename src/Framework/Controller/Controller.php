<?php

namespace TastPHP\Framework\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TastPHP\Common\Kit\Validator;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Debug\Collector\VarCollector;
use TastPHP\Framework\Event\HttpEvent;

class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $html
     * @param array $parameters
     * @return Response
     */
    protected function render($html, $parameters = [])
    {
        $startTime = $this->get('start_time');
        //$chromephp = $this->get('chromePhp');
        $end = microtime(true);
        $totalTime = "run time: " . ($end - $startTime) . "s";
        //$chromephp::info($totalTime);

        if ($this->container['debug']) {
            if ($this->container->singleton('debugbar')->hasCollector('view')) {
                $parameters['模板地址'] = $html;
                $this->container->singleton('debugbar')->getCollector('view')->setData($parameters);
            } else {
                $parameters['模板地址'] = $html;
                $this->container->singleton('debugbar')->addCollector(new VarCollector($parameters));
            }
        }

        $content = $this->container['twig']->render($html, $parameters);
        return new Response($content, 200);
    }

    protected function renderView($html, $parameters = [])
    {
        $startTime = $this->get('start_time');
        //$chromephp = $this->get('chromePhp');
        $end = microtime(true);
        $totalTime = "run time: " . ($end - $startTime) . "s";
        //$chromephp::info($totalTime);
        $content = $this->container['twig']->render($html, $parameters);
        return $content;
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $targetUrl The URL to redirect to
     * @param int $status The status code to use for the Response
     * @param array headers
     *
     * @return RedirectResponse
     */
    protected function redirect($targetUrl, $status = 302, $headers = array())
    {
        return new RedirectResponse($targetUrl, $status, $headers);
    }


    /**
     * @param $routeName    An route name
     * @param array $path An array of path parameters
     * @param array $query An array of query parameters
     * @throws callable|\Exception
     */
    protected function forward($routeName, array $path = [], array $query = [])
    {
        $allRoutes = $this->get('allRoutes');
        if (!$allRoutes[$routeName]) {
            throw new \Exception('routeName error.');
        }
        $routeConfig = $allRoutes[$routeName];
        $blankRoute = $this->get('blankRoute');
        $blankRoute->url = $routeConfig['pattern'];
        $blankRoute->config = $routeConfig['parameters'];
        $blankRoute->parameters = $path;
        $blankRoute->query = $query;
        $blankRoute->dispatch($this->container);
    }

    /**
     * return jsonResponse content
     * @param $data
     * @param int $status
     * @param array $header
     * @return JsonResponse
     */
    protected function json($data, $status = 200, array $header = [], $options = 0)
    {
        return json($data, $status, $header, $options);
    }
}