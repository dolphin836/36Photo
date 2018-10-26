<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;

class Common_model
{
    protected $app;

    protected $table_name;

    function __construct(ContainerInterface $app, $table_name)
    {
        $this->app        = $app;
        $this->table_name = $table_name;
    }

    public function is_has($key, $value)
    {   
        return $this->app->db->has($this->table_name, [$key => $value]);
    }

    public function total($data = [])
    {
        if (isset($data['LIMIT'])) {
            unset($data['LIMIT']);
        }

        if (isset($data['ORDER'])) {
            unset($data['ORDER']);
        }

        return $this->app->db->count($this->table_name, $data);
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["gmt_create" => "DESC"];
        }

        return $this->app->db->select($this->table_name, "*", $data);
    }

    public function add($data = [])
    {
        return $this->app->db->insert($this->table_name, $data);
    }
}


