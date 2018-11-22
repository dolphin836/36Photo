<?php

namespace Dolphin\Ting\Controller\Pic;

use Slim\Http\Request;
use Slim\Http\Response;
use ColorThief\ColorThief as Color;
use Spatie\Color\Rgb as RGB;
use Dolphin\Ting\Constant\Common;

class GetColor extends Pic
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        ini_set('memory_limit', '1024M');

        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $hash = $querys['hash'];

        $pic  = $this->pic_model->record(["hash" => $hash]);

        $color_arr = Color::getPalette($pic['path'], Common::COLOR_COUNT, Common::COLOR_QUALITY);

        foreach ($color_arr as $color) {
            $rgb = 'rgb(' . $color[0] . ', ' . $color[1] . ', ' . $color[2] . ')';
            $hex = (string) RGB::fromString($rgb)->toHex();
            $hex = substr($hex, 1);
  
            $this->pic_model->add_color($hash, $hex);
        }

        return $response->withJson(['code' => 0]);
    }
}
