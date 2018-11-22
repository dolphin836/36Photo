<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Model\Mark_model;
use Dolphin\Ting\Constant\Nav;

class Mark extends \Dolphin\Ting\Controller\Base
{
    protected $mark_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->mark_model = new Mark_model($app);

        $this->nav = Nav::MARK;
    }
}