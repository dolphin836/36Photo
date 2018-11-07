<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Mark_model;

class Mark extends \Dolphin\Ting\Controller\Base
{
    protected $mark_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::MARK;

        $this->mark_model = new Mark_model($app);
    }
}