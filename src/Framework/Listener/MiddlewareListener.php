<?php

namespace TastPHP\Framework\Listener;

use Symfony\Component\EventDispatcher\Event;
use TastPHP\Service\ServiceKernel;
use TastPHP\Framework\Event\AppEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class MiddlewareListener
{
    protected $app;

    public function onMiddlewareAction(Event $event)
    {
        $this->app = \Kernel::getInstance();

        $container = $event->getParameters();
        $csrfToken = $container['csrfToken'];
        $request = $container['symfonyRequest'];

        if (!empty($container['CurrentRoute']) && (isset($container['CurrentRoute']->config['csrf']) && false === $container['CurrentRoute']->config['csrf'])) {
            $request->request->remove("_csrf_token");
            $this->app['app.user'] = $this->getUser($request);
            return true;
        }

        if ("POST" == $request->getMethod()) {
            if ($request->isXmlHttpRequest()) {
                $token = $request->headers->get('X-CSRF-Token');
            } else {
                $token = $request->request->get('_csrf_token');
            }

            if ($csrfToken->validate($token)) {
                $request->request->remove("_csrf_token");
            } else {
                $response = new JsonResponse([
                    'name' => 'badCSRFToken',
                ], 403
                );
                $this->app->singleton('eventDispatcher')->dispatch(AppEvent::RESPONSE, new \TastPHP\Framework\Event\HttpEvent(null, $response));
            }
        }

        $user = $this->getUser($request);

        $this->app['app.user'] = $user;

        $this->app->singleton('twig')->addGlobal('app', ['user' => $user, 'debug' => $this->app['debug']]);
    }

    private function getUserId($request)
    {
        $token = $request->cookies->get('JWT');

        $data = explode('.', $token);

        if (count($data) != 3) {
            return 0;
        }

        if (!empty($token)) {
            $parser = $this->app->singleton('jwtParser');
            $token = $parser->parse((string)$token);
            $key = $this->app->singleton('secret');
            $signer = $this->app->singleton('signer');
            if ($token->verify($signer, $key)) {
                return $token->getClaim('uid');
            }
        }
        return 0;
    }

    private function getUser($request)
    {
        $userId = $this->getUserId($request);

        if ($userId > 0) {
            $user = ServiceKernel::instance()->registerService('User.UserService')->getUser($userId);
            if (isset($user['roles'])) $user['roles'] = json_decode($user['roles'], true);
        } else {
            $user = [];
        }

        return $user;
    }
}