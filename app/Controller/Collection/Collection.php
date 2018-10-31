<?php

namespace Dolphin\Ting\Controller\Collection;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;

class Collection extends \Dolphin\Ting\Controller\Base
{
    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, Table::COLLECTION);

        $this->nav = Nav::COLLECTION;
    }
}