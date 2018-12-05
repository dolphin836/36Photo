<?php

namespace Dolphin\Ting\Controller\Category;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Model\Category_model;
use Dolphin\Ting\Constant\Nav;

class Category extends \Dolphin\Ting\Controller\Base
{
    protected $category_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->category_model = new Category_model($app);

        $this->nav = Nav::CATEGORY;
    }
}