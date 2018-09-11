<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetRecords extends \Dolphin\Ting\Controller\Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = 'mark';

        $this->record = [
                  'name' => [
                'column' => 'name',
                'format' => 'string'
            ],
                 'count' => [
                'column' => 'count',
                'format' => 'string'
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

        $this->is_search = false;

        $this->request   = $request;

        $this->respond();
    }
}