<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Collection_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::COLLECTION;
    }

    /**
     * 添加一条图片记录
     */
    public function add_picture($collection_code, $picture_hash)
    {
        return $this->app->db->insert(Table::PICTURE_COLLECTION, [
            'collection_code' => $collection_code,
               'picture_hash' => $picture_hash
        ]);
    }

    // 数量加一
    public function count_plus($collection_code)
    {
        return $this->app->db->update($this->table_name, [
            'count[+]' => 1
        ], [
            'code' => $collection_code
        ]);
    }

    // 数量减一
    public function count_sub($collection_code)
    {
        return $this->app->db->update($this->table_name, [
            'count[-]' => 1
        ], [
            'code' => $collection_code
        ]);
    }

    /**
     * 删除图片的专题记录
     */
    public function delete_pic($hash)
    {
        return $this->app->db->delete(Table::PICTURE_COLLECTION, [
            "picture_hash" => $hash
        ]);
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["gmt_create" => "DESC"];
        }

        if (isset($data['name'])) {
            $data['name[~]'] = $data['name'];
            unset($data['name']);
        }

        if (isset($data['public'])) {
            $data['is_public'] = $data['public'];
            unset($data['public']);
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

        if (isset($data['name'])) {
            $data['name[~]'] = $data['name'];
            unset($data['name']);
        }

        if (isset($data['public'])) {
            $data['is_public'] = $data['public'];
            unset($data['public']);
        }

        return $this->app->db->count($this->table_name, $data);
    }

    public function pic($data)
    {
        if (isset($data['code'])) {
            $data[Table::PICTURE_COLLECTION . ".collection_code"] = $data['code'];
            unset($data['code']);
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

        return $this->app->db->select(Table::PICTURE_COLLECTION, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ],[
            Table::PICTURE_COLLECTION . ".picture_hash"
        ], $data);
    }

    public function pic_total($data)
    {
        if (isset($data['LIMIT'])) {
            unset($data['LIMIT']);
        }

        if (isset($data['ORDER'])) {
            unset($data['ORDER']);
        }
        
        if (isset($data['code'])) {
            $data[Table::PICTURE_COLLECTION . ".collection_code"] = $data['code'];
            unset($data['code']);
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

        return $this->app->db->count(Table::PICTURE_COLLECTION, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ], [
            Table::PICTURE_COLLECTION . ".id"
        ], $data);
    }
}


