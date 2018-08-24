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
                'format' => 'string'
            ],
                  'name' => [
                'column' => 'name',
                'format' => 'string'
            ],
              'nickname' => [
                'column' => 'nickname',
                'format' => 'string'
            ],
                 'phone' => [
                'column' => 'phone',
                'format' => 'string'
            ],
                 'email' => [
                'column' => 'email',
                'format' => 'string'
            ],
                'avatar' => [
                'column' => 'avatar',
                'format' => 'pre',
                  'data' => '/'
            ],
             'is_wechat' => [
                'column' => 'open_id',
                'format' => 'bool'
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
                ]
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
                ]
            ],
            'last_login' => [
                'column' => 'last_login',
                'format' => 'datetime'
            ],
            'gmt_create' => [
                'column' => 'gmt_create',
                'format' => 'string'
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