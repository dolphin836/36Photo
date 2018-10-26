<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;

class Categroy extends \Dolphin\Ting\Controller\Base
{
    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, Table::CATEGROY);

        $this->nav = Nav::CATEGROY;
    }
}