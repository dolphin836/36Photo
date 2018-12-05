<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Common;

class Pic_model extends Common_model
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = Table::PICTURE;
    }

    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = [Table::PICTURE . ".id" => "DESC"];
        }

        if (isset($data['category'])) {
            $data[Table::PICTURE . '.category_code'] = $data['category'];
            unset($data['category']);
        }

        if (isset($data['uuid'])) {
            $data[Table::PICTURE . '.uuid'] = $data['uuid'];
            unset($data['uuid']);
        }

        // 生产环境只展示已上传阿里云的记录
        if (getenv('DEBUG') === 'FALSE') {
            $data[Table::PICTURE . '.is_oss'] = Common::IS_OSS;
        }

        return $this->app->db->select(Table::PICTURE, [
            "[>]" . Table::CATEGORY => ["category_code" => "code"],
            "[>]" . Table::USER     => ["uuid" => "uuid"],
        ], [
            Table::PICTURE  . ".hash",
            Table::PICTURE  . ".width",
            Table::PICTURE  . ".height",
            Table::PICTURE  . ".size",
            Table::PICTURE  . ".path",
            Table::PICTURE  . ".is_oss",
            Table::PICTURE  . ".browse",
            Table::PICTURE  . ".download",
            Table::PICTURE  . ".collect",
            Table::PICTURE  . ".like",
            Table::PICTURE  . ".is_public",
            Table::PICTURE  . ".gmt_create",
            Table::CATEGORY . ".code",
            Table::CATEGORY . ".name",
            Table::USER     . ".uuid",
            Table::USER     . ".username"
        ], $data);
    }

    public function random($data = [])
    {
        // 生产环境只展示已上传阿里云的记录
        if (getenv('DEBUG') === 'FALSE') {
            $data[Table::PICTURE . '.is_oss'] = Common::IS_OSS;
        }

        return $this->app->db->rand(Table::PICTURE, [
            "[>]" . Table::CATEGORY => ["category_code" => "code"],
            "[>]" . Table::USER     => ["uuid" => "uuid"],
        ], [
            Table::PICTURE  . ".hash",
            Table::PICTURE  . ".width",
            Table::PICTURE  . ".height",
            Table::PICTURE  . ".size",
            Table::PICTURE  . ".path",
            Table::PICTURE  . ".is_oss",
            Table::PICTURE  . ".browse",
            Table::PICTURE  . ".download",
            Table::PICTURE  . ".collect",
            Table::PICTURE  . ".like",
            Table::PICTURE  . ".is_public",
            Table::PICTURE  . ".gmt_create",
            Table::CATEGORY . ".code",
            Table::CATEGORY . ".name",
            Table::USER     . ".uuid",
            Table::USER     . ".username"
        ], $data);
    }

    public function record($data = [])
    {
        return $this->app->db->get(Table::PICTURE, [
            "[>]" . Table::CATEGORY => ["category_code" => "code"],
            "[>]" . Table::USER     => ["uuid" => "uuid"],
        ], [
            Table::PICTURE  . ".hash",
            Table::PICTURE  . ".width",
            Table::PICTURE  . ".height",
            Table::PICTURE  . ".size",
            Table::PICTURE  . ".path",
            Table::PICTURE  . ".is_oss",
            Table::PICTURE  . ".browse",
            Table::PICTURE  . ".download",
            Table::PICTURE  . ".collect",
            Table::PICTURE  . ".like",
            Table::PICTURE  . ".is_public",
            Table::PICTURE  . ".gmt_create",
            Table::CATEGORY . ".code",
            Table::CATEGORY . ".name",
            Table::USER     . ".uuid",
            Table::USER     . ".username"
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

        // 生产环境只展示已上传阿里云的记录
        if (getenv('DEBUG') === 'FALSE') {
            $data['is_oss'] = Common::IS_OSS;
        }

        return $this->app->db->count($this->table_name, $data);
    }

    public function color_hash_total($color)
    {
        return $this->app->db->count(Table::PICTURE_COLOR, [
            'color' => $color
        ]);
    }

    public function color_hash($data)
    {
        return $this->app->db->select(Table::PICTURE_COLOR, [
            'picture_hash'
        ], $data);
    }

    public function pic_color($hash)
    {
        return $this->app->db->select(Table::PICTURE_COLOR, [
            'color'
        ], [
            'picture_hash' => $hash
        ]);
    }

    public function pic_mark($hash)
    {
        return $this->app->db->select(Table::MARK, [
            "[>]" . Table::PICTURE_MARK => ["id" => "mark_id"]
        ], [
            Table::MARK . ".name",
            Table::MARK . ".count"
        ], [
            Table::PICTURE_MARK . ".picture_hash" => $hash
        ]);
    }

    public function mark_hash_total($mark)
    {
        return $this->app->db->count(Table::PICTURE_MARK, [
            "[>]" . Table::MARK => ["mark_id" => "id"]
        ], [
            Table::PICTURE_MARK . ".id"
        ], [
            Table::MARK . ".name" => $mark
        ]);
    }

    public function mark_hash($data)
    {
        return $this->app->db->select(Table::PICTURE_MARK, [
            "[>]" . Table::MARK => ["mark_id" => "id"]
        ], [
            Table::PICTURE_MARK . ".picture_hash"
        ], $data);
    }
}


