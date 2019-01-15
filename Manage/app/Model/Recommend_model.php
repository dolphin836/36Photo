<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Recommend_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::PICTURE_RECOMMEND;
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["gmt_create" => "DESC"];
        }

        if (isset($data['day'])) {
            $data['gmt_create[>=]'] = $data['day'] . ' 00:00:00';
            $data['gmt_create[<=]'] = $data['day'] . ' 23:59:59';
            unset($data['day']);
        }

        return $this->app->db->select($this->table_name, "*", $data);
    }

    public function total($data = [])
    {
        if (isset($data['LIMIT'])) {
            unset($data['LIMIT']);
        }

        if (isset($data['ORDER'])) {
            unset($data['ORDER']);
        }

        if (isset($data['day'])) {
            $data['gmt_create[>=]'] = $data['day'] . ' 00:00:00';
            $data['gmt_create[<=]'] = $data['day'] . ' 23:59:59';
            unset($data['day']);
        }

        return $this->app->db->count($this->table_name, $data);
    }
}


