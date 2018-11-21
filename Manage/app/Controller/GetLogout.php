<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class GetLogout
{
    private $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke(Request $request, Response $response, $args)
    {   
        if ($this->app->session->exists('uuid')) {
            $this->app->session->delete('uuid', $user['uuid']);
            $this->app->session->delete('name', $user['name']);
        }

        return $response->withRedirect('/login', 302);

    }
}