<?php

use TastPHP\Framework\Kernel;
use Symfony\Component\HttpFoundation\JsonResponse;

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $abstract
     * @return mixed|\Group\App\App
     */
    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return Kernel::getInstance();
        }

        return Kernel::getInstance()->make($abstract);
    }
}

if (!function_exists('ajax')) {
    /**
     * ajax return.返回一个json数组，并结束整个请求。
     *
     * @param  string  $message
     * @param  array     $data
     * @param  int   $code
     * @return void
     *
     */
    function ajax($message = '', $data = [], $code = 200)
    {
        app()->setResponse(new JsonResponse(['message' => $message, 'data' => $data, 'code' => $code], 200));
        app()->run();
        exit;
    }
}

if (!function_exists('json')) {
    /**
     * 返回一个json response
     *
     * @param  array     $data
     * @param  int   $status
     * @param  array     $headers
     * @param  int   $options
     * @return object \JsonResponse
     *
     */
    function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }
}
