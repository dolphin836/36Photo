<?php

namespace Dolphin\Ting\Controller\Collection;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Collection_model;

class Collection extends \Dolphin\Ting\Controller\Base
{
    protected $collection_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::COLLECTION;

        $this->collection_model = new Collection_model($app);
    }
}