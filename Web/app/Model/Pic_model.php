<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;

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

        if (isset($data['categroy'])) {
            $data[Table::PICTURE . '.categroy_code'] = $data['categroy'];
            unset($data['categroy']);
        }

        if (isset($data['uuid'])) {
            $data[Table::PICTURE . '.uuid'] = $data['uuid'];
            unset($data['uuid']);
        }

        // 生产环境只展示已上传阿里云的记录
        if (getenv('DEBUG') === 'FALSE') {
            $data[Table::PICTURE . '.is_oss'] = 1;
        }

        return $this->app->db->select(Table::PICTURE, [
            "[>]" . Table::CATEGROY => ["categroy_code" => "code"],
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
            Table::CATEGROY . ".code",
            Table::CATEGROY . ".name",
            Table::USER     . ".uuid",
            Table::USER     . ".username"
        ], $data);
    }

    public function record($data = [])
    {
        return $this->app->db->get(Table::PICTURE, [
            "[>]" . Table::CATEGROY => ["categroy_code" => "code"],
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
            Table::CATEGROY . ".code",
            Table::CATEGROY . ".name",
            Table::USER     . ".uuid",
            Table::USER     . ".username"
        ], $data);
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
}


