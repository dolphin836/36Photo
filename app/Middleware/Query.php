<?php

namespace Dolphin\Ting\Middleware;

use Psr\Container\ContainerInterface as ContainerInterface;

class Query
{
    protected $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke($request, $response, $next)
    {
        if ($request->isGet()) {
            $uri = $request->getUri();

            parse_str($uri->getQuery(), $querys);

            $page   = 1;
            $order  = 'DESC';
            $filter = [];

            foreach ($querys as $key => $value) {
                if ($key == 'page' && $value > 0) {
                    $page = (int) $value;
                }

                if ($key == 'order' && in_array($value, ['ASC', 'DESC'])) {
                    $order = $value;
                }

                if (strpos($key, 'search_') !== false && $value != '') {
                    $filter[$key] = $value;
                }
            }

            $request = $request->withAttribute('page', $page);
            $request = $request->withAttribute('order', $order);
            $request = $request->withAttribute('search', $filter);
        }

        $response = $next($request, $response);

        return $response;
    }
}