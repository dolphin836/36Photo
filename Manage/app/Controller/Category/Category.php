<?php

namespace Dolphin\Ting\Controller\Category;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Category_model;

class Category extends \Dolphin\Ting\Controller\Base
{
    protected $category_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::CATEGORY;

        $this->category_model = new Category_model($app);
    }
}