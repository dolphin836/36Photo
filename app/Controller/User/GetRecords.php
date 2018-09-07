<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetRecords extends \Dolphin\Ting\Controller\Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = 'user';

        $this->record = [
                  'uuid' => [
                'column' => 'uuid',
                'format' => 'string'
            ],
              'username' => [
                'column' => 'username',
                'format' => 'string',
                  'name' => '用户名'
            ],
                  'name' => [
                'column' => 'name',
                'format' => 'string',
                  'mark' => '[~]',
                  'name' => '姓 名',
             'is_search' => true,
               'is_show' => true
            ],
              'nickname' => [
                'column' => 'nickname',
                'format' => 'string',
                  'name' => '昵 称'
            ],
                 'phone' => [
                'column' => 'phone',
                'format' => 'string',
                  'mark' => '[~]',
                  'name' => '手 机',
             'is_search' => true,
               'is_show' => true
            ],
                 'email' => [
                'column' => 'email',
                'format' => 'string',
                  'mark' => '[~]',
                  'name' => '邮 箱',
             'is_search' => true
            ],
                'avatar' => [
                'column' => 'avatar',
                'format' => 'pre',
                  'data' => '/',
                  'name' => '头 像'
            ],
             'is_wechat' => [
                'column' => 'open_id',
                'format' => 'bool',
                  'name' => '微信用户'
            ],
                'client' => [
                'column' => 'client',
                'format' => 'string'
            ],
           'client_name' => [
                'column' => 'client',
                'format' => 'enum',
                  'data' => [
                    '后台',
                    'PC',
                    '移动',
                    'iOS',
                    'Android',
                    '微信小程序'  
                ],
               'is_show' => true,
                  'name' => '来 源',
             'is_search' => true
            ],
                 'group' => [
                'column' => 'group',
                'format' => 'string'
            ],
            'group_name' => [
                'column' => 'group',
                'format' => 'enum',
                  'data' => [
                    '普通用户',
                    '管理员',
                    '超级管理员'
                ],
               'is_show' => true,
                  'name' => '用户组',
             'is_search' => true
            ],
            'last_login' => [
                'column' => 'last_login',
                'format' => 'datetime',
                  'name' => '最近登录',
               'is_show' => true
            ],
            'gmt_create' => [
                'column' => 'gmt_create',
                'format' => 'string',
                  'name' => '创建时间',
               'is_show' => true
            ]
        ];
    }

    public function __invoke($request, $response, $args)
    {  
        $this->is_page   = true;

        $this->is_search = true;

        $this->request   = $request;

        $this->respond();
    }
}