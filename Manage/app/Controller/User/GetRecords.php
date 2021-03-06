<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;

class GetRecords extends User
{
  private $columns = [
    '基本信息',
    '用户组',
    '来源',
    '创建时间',
    '最近登录'
  ];

  function __construct(ContainerInterface $app)
  {
    parent::__construct($app);

    $this->nav_route = Nav::RECORDS;
  }

  public function __invoke(Request $request, Response $response, $args)
  {  
    $page   = $request->getAttribute('page');
    // 检索
    $search = $request->getAttribute('search');
    // 排序
    $order  = $request->getAttribute('order');

    $query  = '';

    if (! empty($search)) {
      $query .= '&';
      $query .= http_build_query($search);
    }

    if ($order != '') {
      $query .= '&order=' . $order;
    }

    $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];

    $search['ORDER'] = ["gmt_create" => $order];

    $records = $this->user_model->records($search);

    $user    = [];

    foreach ($records as $record) {
      $user[] = [
               'uuid' => $record['uuid'],
               'name' => $record['name'],
              'phone' => $record['phone'],
              'email' => $record['email'],
         'gmt_create' => $record['gmt_create'],
         'last_login' => $record['last_login'] ? date("Y-m-d H:i:s", $record['last_login']) : '',
          'is_wechat' => $record['open_id'] === '' ? false : true,
              'group' => $record['group'],
             'client' => $record['client'],
         'group_name' => $this->group[$record['group']],
        'client_name' => $this->client[$record['client']]
      ];
    }

    $data = [
      "records" => $user,
      "columns" => $this->columns,
        'group' => $this->group,
       'client' => $this->client,
         'text' => $request->getAttribute('text'),
         "page" => Page::reder('/user/records', $this->user_model->total($search), $page, Common::PAGE_COUNT, $query)
    ];

    $this->respond('User/Records', $data);
  }
}