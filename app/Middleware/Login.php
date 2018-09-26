<?php

namespace Dolphin\Ting\Middleware;

use Psr\Container\ContainerInterface as ContainerInterface;

class Login
{
    protected $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();

        $route = $uri->getPath();

        if ($route != '/login' && ! $this->app->session->exists('uuid')) {
            return $response->withRedirect('/login', 302);
        }

        $response = $next($request, $response);

        return $response;
    }
}