<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Pic extends \Dolphin\Ting\Controller\Base
{
    protected $table_name = Table::PICTURE;

    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);
    }
}