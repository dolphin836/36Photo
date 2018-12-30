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
            $data["ORDER"] = [Table::MARK . ".gmt_create" => "DESC"];
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

    public function pic_total($mark_id)
    {
        return $this->app->db->count(Table::PICTURE_MARK, [
           "mark_id" => $mark_id
        ]);
    }

    public function pic($data)
    {
        return $this->app->db->select(Table::PICTURE_MARK, [
            "picture_hash"
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
}


