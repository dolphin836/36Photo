<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Common;

class Color_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::COLOR;
    }
}


