<?php

namespace Dolphin\Ting\Controller\Color;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Nav;
use InvertColor\Color as InvColor;
use InvertColor\Exceptions\InvalidColorFormatException;

class GetRecords extends Color
{
    const PAGE_COUNT = 24;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav_route = Nav::RECORDS;
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $page   = $request->getAttribute('page');
        $search = $request->getAttribute('search');
        $text   = $request->getAttribute('text');

        $query  = '';

        if (! empty($text)) {
            $query .= '&';
            $query .= http_build_query($text);
        }

        if (isset($search['recommend']) && (int) $search['recommend'] === 1) {
            $this->nav_route = Nav::RECOMMEND;
        }

        $search['LIMIT'] = [self::PAGE_COUNT * ($page - 1), self::PAGE_COUNT];

        $colors  = $this->color_model->records($search);

        $records = [];

        foreach ($colors as $color) {
            // 计算反色，用于展示文字的颜色
            try {
                $color['fontcolor'] = InvColor::fromHex('#' . $color['color'])->invert();
            } catch (InvalidColorFormatException $e) {
                // 默认白色
                $color['fontcolor'] = '#FFFFFF';
            }

            $records[] = $color;
        }

        $data = [
              "records" => $records,
                 "page" => Page::reder('/color/records', $this->color_model->total($search), $page, self::PAGE_COUNT, $query)
        ];

        $this->respond('Color/Records', $data);
    }
}