<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Category_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::CATEGORY;
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["count" => "DESC"];
        }

        if (isset($data['name'])) {
            $data['name[~]'] = $data['name'];
            unset($data['name']);
        }

        if (isset($data['code'])) {
            $data['code[~]'] = $data['code'];
            unset($data['code']);
        }

        return $this->app->db->select($this->table_name, "*", $data);
    }

    public function is_real_has($code)
    {   
        return $this->app->db->has($this->table_name, [
            'code' => $code
        ]);
    }

    // 数量加一
    public function count_plus($category_code)
    {
        return $this->app->db->update($this->table_name, [
            'count[+]' => 1
        ], [
            'code' => $category_code
        ]);
    }

    // 数量减一
    public function count_sub($category_code)
    {
        return $this->app->db->update($this->table_name, [
            'count[-]' => 1
        ], [
            'code' => $category_code
        ]);
    }
}


