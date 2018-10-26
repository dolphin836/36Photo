<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;

class Mark extends \Dolphin\Ting\Controller\Base
{
    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, Table::MARK);

        $this->nav = Nav::MARK;
    }
}