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

        $this->table_name = Table::PICTURE_COLOR;
    }

    public function records($data = [])
    {
        // 原始语句
        $sql = "SELECT `color` FROM `" . $this->table_name . "` GROUP BY `color` ORDER BY COUNT(`color`) DESC LIMIT " . Common::COLOR_MAX_COUNT;

        return $this->app->db->query($sql)->fetchAll();
    }
}


