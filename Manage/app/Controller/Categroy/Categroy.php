<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Categroy_model;

class Categroy extends \Dolphin\Ting\Controller\Base
{
    protected $categroy_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, Table::CATEGROY);

        $this->nav = Nav::CATEGROY;

        $this->categroy_model = new Categroy_model($app);
    }
}