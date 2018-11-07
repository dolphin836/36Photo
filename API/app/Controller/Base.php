<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;

class Base
{
    protected $app;
    
    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    protected function respond($code = 0, $data = [], $note = '')
    {
        if ($note === '') {
            $note = Common::ERROR_NOTE[$code];
        }

        $json = [
            'code' => $code,
            'note' => $note,
            'data' => $data,
        ];

        return $json;
    }
}

