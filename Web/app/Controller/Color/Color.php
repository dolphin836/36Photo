<?php

namespace Dolphin\Ting\Controller\Color;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Model\Color_model;
use Dolphin\Ting\Constant\Nav;

class Color extends \Dolphin\Ting\Controller\Base
{
    protected $color_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->color_model = new Color_model($app);

        $this->nav = Nav::COLOR;
    }
}