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

            if (isset($querys['search_start']) && ! isset($querys['search_end'])) {
                $querys['search_end'] =  strtotime($querys['search_start']) >= time() ? $querys['search_start'] : date("Y-m-d H:i:s", time());
            }

            if (isset($querys['search_end']) && ! isset($querys['search_start'])) {
                $querys['search_start'] =  strtotime($querys['search_end']) >= time() ? date("Y-m-d H:i:s", time()) : date("Y-m-d H:i:s", strtotime(" -1 month"));
            }

            $page   = 1;
            $order  = 'DESC';
            $sort   = '';
            $filter = [];
            $text   = [];

            foreach ($querys as $key => $value) {
                if ($key == 'page' && $value > 0) {
                    $page = (int) $value;
                }

                if ($key == 'order' && in_array($value, ['ASC', 'DESC'])) {
                    $order = $value;
                }

                if ($key == 'sort' && $value != '') {
                    $sort = $value;
                }

                if (strpos($key, 'search_') !== false && $value != '') {
                    $k = explode("_", $key);

                    if (isset($k[2]) && $k[2] != '') {
                        $filter[$k[1] . '[' . $k[2] . ']'] = $value;
                    } else {
                        $filter[$k[1]] = $value;
                    }

                    $text[$key] = $value;
                }
            }

            $request = $request->withAttribute('page',   $page);
            $request = $request->withAttribute('sort',   $sort);
            $request = $request->withAttribute('order',  $order);
            $request = $request->withAttribute('search', $filter);
            $request = $request->withAttribute('text',   $text);
        }

        $response = $next($request, $response);

        return $response;
    }
}