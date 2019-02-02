<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Color_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::COLOR;
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["count" => "DESC"];
        }

        if (isset($data['recommend'])) {
            $data['is_recommend'] = $data['recommend'];
            unset($data['recommend']);
        }

        return $this->app->db->select($this->table_name, '*', $data);
    }

    public function total($data = [])
    {
        if (isset($data['LIMIT'])) {
            unset($data['LIMIT']);
        }

        if (isset($data['ORDER'])) {
            unset($data['ORDER']);
        }

        if (isset($data['recommend'])) {
            $data['is_recommend'] = $data['recommend'];
            unset($data['recommend']);
        }

        return $this->app->db->count($this->table_name, $data);
    }

    public function pic($data)
    {
        if (isset($data['color'])) {
            $data[Table::PICTURE_COLOR . ".color"] = $data['color'];
            unset($data['color']);
        }

        if (isset($data['category'])) {
            $data[Table::PICTURE . ".category_code"] = $data['category'];
            unset($data['category']);
        }

        if (isset($data['oss'])) {
            $data[Table::PICTURE . ".is_oss"] = $data['oss'];
            unset($data['oss']);
        }

        if (isset($data['start'])) {
            $data[Table::PICTURE . '.gmt_create[>=]'] = $data['start'];
            unset($data['start']);
        }

        if (isset($data['end'])) {
            $data[Table::PICTURE . '.gmt_create[<=]'] = $data['end'];
            unset($data['end']);
        }

        return $this->app->db->select(Table::PICTURE_COLOR, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ],[
            Table::PICTURE_COLOR . ".picture_hash"
        ], $data);
    }

    public function pic_total($data)
    {
        if (isset($data['color'])) {
            $data[Table::PICTURE_COLOR . ".color"] = $data['color'];
            unset($data['color']);
        }

        if (isset($data['category'])) {
            $data[Table::PICTURE . ".category_code"] = $data['category'];
            unset($data['category']);
        }

        if (isset($data['oss'])) {
            $data[Table::PICTURE . ".is_oss"] = $data['oss'];
            unset($data['oss']);
        }

        if (isset($data['start'])) {
            $data[Table::PICTURE . '.gmt_create[>=]'] = $data['start'];
            unset($data['start']);
        }

        if (isset($data['end'])) {
            $data[Table::PICTURE . '.gmt_create[<=]'] = $data['end'];
            unset($data['end']);
        }

        if (isset($data['LIMIT'])) {
            unset($data['LIMIT']);
        }

        if (isset($data['ORDER'])) {
            unset($data['ORDER']);
        }

        return $this->app->db->count(Table::PICTURE_COLOR, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ], [
            Table::PICTURE_COLOR . ".id"
        ], $data);
    }
}


