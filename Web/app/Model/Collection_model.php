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
}


