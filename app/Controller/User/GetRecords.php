<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Model\Common_model;
use Dolphin\Ting\Constant\Common;

class GetRecords extends User
{
  private $common_model;

  private $columns = [
            'path' => '缩略图',
           'width' => '宽',
          'height' => '高',
            'size' => '大小',
      'gmt_create' => '创建时间'
  ];

  function __construct(ContainerInterface $app)
  {
    parent::__construct($app);

    $this->common_model = new Common_model($app, $this->table_name);
  }

  public function __invoke($request, $response, $args)
  {  
    $page = $request->getAttribute('page');

    $user = [];

    $records = $this->common_model->records([
      "LIMIT" => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
    ]);

    foreach ($records as $record) {
      $user[] = [
               'uuid' => $record['uuid'],
               'name' => $record['name'],
              'phone' => $record['phone'],
              'email' => $record['email'],
         'gmt_create' => $record['gmt_create'],
         'last_login' => $record['last_login'],
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
         "page" => Page::reder('/user/records', $this->common_model->total(), $page, Common::PAGE_COUNT, '')
    ];

    $this->respond('User/Records', $data);
  }
}