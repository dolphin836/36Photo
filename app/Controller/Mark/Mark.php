<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Mark extends \Dolphin\Ting\Controller\Base
{
    protected $table_name = Table::MARK;

    protected $conf = [
        
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);
    }
}