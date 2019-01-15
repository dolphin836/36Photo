<?php

namespace Dolphin\Ting\Model;

use Psr\Container\ContainerInterface as ContainerInterface;

class Common_model
{
    protected $app;

    protected $table_name;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    /**
     * 记录是否存在
     */
    public function is_has($key, $value)
    {   
        return $this->app->db->has($this->table_name, [$key => $value]);
    }

    /**
     * 记录总数
     */
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

    /**
     * 记录列表
     */
    public function records($data = [])
    {
        if (! isset($data["ORDER"])) {
            $data["ORDER"] = ["gmt_create" => "DESC"];
        }

        return $this->app->db->select($this->table_name, "*", $data);
    }

    /**
     * 记录详情
     */
    public function record($data = [])
    {
        return $this->app->db->get($this->table_name, "*", $data);
    }

    /**
     * 新增记录
     */
    public function add($data = [])
    {
        return $this->app->db->insert($this->table_name, $data);
    }

    /**
     * 删除记录
     */
    public function delete($data = [])
    {
        return $this->app->db->delete($this->table_name, $data);
    }

    /**
     * 更新记录
     */
    public function modify($data = [], $where = [])
    {
        return $this->app->db->update($this->table_name, $data, $where);
    }
}


