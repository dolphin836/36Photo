<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page as Page;
use Ramsey\Uuid\Uuid as U;

class Base
{
    protected $app;
    // 每页显示的记录数量
    protected $count = 20;
    // 数据库
    protected $table_name = '';
    protected $record = [];
    // 是否开启数据分页
    protected $is_page = false;
    // 当前页数
    private $page = 1;
    // 是否开启数据检索
    protected $is_search = false;
    // 模版路径
    private $html = '';
    // 请求
    protected $request;
    // 数据操作
    private $method = '';

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    protected function respond()
    {
        $this->conf();

        // 新增和更新进行 CSRF 校验
        if ($this->method == 'records' || $this->method == 'modify') {
            $data['csrf'] = [
                 'name_key' => 'next_name',
                'value_key' => 'next_value',
                     'name' => $this->request->getAttribute('next_name'),
                    'value' => $this->request->getAttribute('next_value')
            ];
        }
        // 列表 - 手动设置是否启用数据分页和数据检索
        if ($this->method == 'records') {
            $data['records'] = $this->records($this->record);

            // 数据分页
            if ($this->is_page) {
                $data['page'] = Page::reder('/user/records', $this->total(), $this->page, $this->count);
            }
            // 数据检索
            if ($this->is_search) {
                $data['search'] = $this->search;
            }
        }

        // 公共数据
        // 页面信息
        $data['site'] = [
            'web_name' => getenv('WEB_NAME')
        ];
        // class
        $data['class'] = ['blue', 'azure', 'indigo', 'purple', 'pink', 'orange'];

        // Flash Data
        // 表单验证错误信息
        if ($this->app->flash->hasMessage('form_v_error')) {
            $data['form_v_error'] = $this->app->flash->getFirstMessage('form_v_error');
        }
        // 表单数据
        if ($this->app->flash->hasMessage('form_data')) {
            $data['form_data'] = $this->app->flash->getFirstMessage('form_data');
        }
        // 系统消息
        if ($this->app->flash->hasMessage('note')) {
            $data['note'] = $this->app->flash->getFirstMessage('note');
        }

        echo $this->app->template->render($this->html, $data);
    }

    /**
     * 生成 32 位随机字符串
     */
    protected function random_code()
    {
        return str_replace('-', '', U::uuid4()->toString()); 
    }

    /**
     * 获取记录总数
     */
    private function total()
    {
        return $this->app->db->count($this->table_name);
    }

    /**
     * 获取记录列表
     */
    private function records($record = [])
    {
        $where = [];

        if (! empty($this->search)) {
            foreach ($this->search as $key => $value) {
                if ($key == 'search_start') {
                    $where['gmt_create[>=]'] = $value;

                    continue;
                }

                if ($key == 'search_end') {
                    $where['gmt_create[<=]'] = $value;

                    continue;
                }

                $where[substr($key, 7) . '[~]'] = $value;
            }
        }

        $where["ORDER"] = ["id" => $this->order];
        $where["LIMIT"] = [$this->count * ($this->page - 1), $this->count];

        $results = $this->app->db->select($this->table_name, $this->select($record), $where);

        $records = [];

        foreach ($results as $result) {
            $temp = [];

            foreach ($record as $name => $data) {
                $origin = $result[$data['column']];

                $format = '';

                if ($data['format'] == 'string') {
                    $format = $origin;
                }

                if ($data['format'] == 'pre') {
                    $format = $data['data'] . $origin;
                }

                if ($data['format'] == 'bool') {
                    $format = $origin ? true : false;
                }

                if ($data['format'] == 'enum') {
                    $format = $data['data'][$origin];
                }

                if ($data['format'] == 'datetime') {
                    $format = $origin ? date("Y-m-d H:i:s", $origin) : '';
                }

                $temp[$name] = $format;
            }

            $records[] = $temp;
        }

        return $records;
    }

    /**
     * 获取需要检索的字段
     */
    private function select($record = [])
    {
        $columns = [];

        foreach ($record as $r) {
            if (! in_array($r['column'], $columns)) {
                $columns[] = $r['column'];
            }
        }

        return $columns;
    }

    /**
     * 根据请求信息设置相关信息
     */
    private function conf()
    {
        $methods = ['add', 'records', 'record', 'modify'];
        // 设置页数
        $this->page   = $this->request->getAttribute('page');
        // 设置排序方式
        $this->order  = $this->request->getAttribute('order');
        // 设置检索数据
        $this->search = $this->request->getAttribute('search');
        // 设置模版路径
        $path = explode('/', $this->request->getUri()->getPath());

        $name = '';

        foreach ($path as $p) {
            $name .= $p ? ucwords($p) . '\\' : '';

            if (in_array($p, $methods)) {
                $this->method = $p;
            }
        }

        $name = substr($name, 0, strlen($name) - 1) . '.html';

        $this->html = $name;
    }
}

