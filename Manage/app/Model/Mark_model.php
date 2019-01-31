<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

class Mark_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::MARK;
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = [Table::MARK . ".count" => "DESC"];
        }

        if (isset($data['name'])) {
            $data[Table::MARK . '.name[~]'] = $data['name'];
            unset($data['name']);
        }

        if (isset($data['category'])) {
            $data[Table::MARK . '.category_code'] = $data['category'];
            unset($data['category']);
        }

        if (isset($data['start'])) {
            $data[Table::MARK . '.gmt_create[>=]'] = $data['start'];
            unset($data['start']);
        }

        if (isset($data['end'])) {
            $data[Table::MARK . '.gmt_create[<=]'] = $data['end'];
            unset($data['end']);
        }

        return $this->app->db->select($this->table_name, [
            "[>]" . Table::CATEGORY => ["category_code" => "code"]
        ], [
            Table::CATEGORY . ".name(category_name)",
            Table::CATEGORY . ".count(category_count)",
            Table::MARK . ".id",
            Table::MARK . ".name",
            Table::MARK . ".count",
            Table::MARK . ".category_code",
            Table::MARK . ".gmt_create"
        ], $data);
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
            $data[Table::MARK . '.name[~]'] = $data['name'];
            unset($data['name']);
        }

        if (isset($data['category'])) {
            $data[Table::MARK . '.category_code'] = $data['category'];
            unset($data['category']);
        }

        if (isset($data['start'])) {
            $data[Table::MARK . '.gmt_create[>=]'] = $data['start'];
            unset($data['start']);
        }

        if (isset($data['end'])) {
            $data[Table::MARK . '.gmt_create[<=]'] = $data['end'];
            unset($data['end']);
        }

        return $this->app->db->count($this->table_name, $data);
    }

    public function record($data = [])
    {
        return $this->app->db->get($this->table_name, [
            "[>]" . Table::CATEGORY => ["category_code" => "code"]
        ], [
            Table::CATEGORY . ".name(category_name)",
            Table::MARK . ".name",
            Table::MARK . ".count",
            Table::MARK . ".category_code"
        ], $data);
    }

    public function pic_mark($hash)
    {
        return $this->app->db->select(Table::MARK, [
            "[>]" . Table::PICTURE_MARK => ["id" => "mark_id"]
        ], [
            Table::MARK . ".id",
            Table::MARK . ".name"
        ], [
            Table::PICTURE_MARK . ".picture_hash" => $hash
        ]);
    }

    public function pic_total($data)
    {
        if (isset($data['mark_id'])) {
            $data[Table::PICTURE_MARK . ".mark_id"] = $data['mark_id'];
            unset($data['mark_id']);
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

        return $this->app->db->count(Table::PICTURE_MARK, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ], [
            Table::PICTURE_MARK . ".id"
        ], $data);
    }

    public function pic($data)
    {
        if (isset($data['mark_id'])) {
            $data[Table::PICTURE_MARK . ".mark_id"] = $data['mark_id'];
            unset($data['mark_id']);
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

        return $this->app->db->select(Table::PICTURE_MARK, [
            "[>]" . Table::PICTURE => ["picture_hash" => "hash"]
        ],[
            Table::PICTURE_MARK . ".picture_hash"
        ], $data);
    }

    /**
     * 删除图片的标签
     */
    public function delete_pic($hash)
    {
        return $this->app->db->delete(Table::PICTURE_MARK, [
            "picture_hash" => $hash
        ]);
    }

    /**
     * 删除某标签所有的图片关联记录
     */
    public function delete_mark_pic($mark_id)
    {
        return $this->app->db->delete(Table::PICTURE_MARK, [
            "mark_id" => $mark_id
        ]);
    }
}


