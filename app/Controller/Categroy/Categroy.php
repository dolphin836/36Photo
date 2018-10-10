<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;

class Categroy extends \Dolphin\Ting\Controller\Base
{
    protected $table_name = Table::CATEGROY;

    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);
    }
}