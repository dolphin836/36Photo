<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetRecords extends \Dolphin\Ting\Controller\Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = 'picture';

        $this->record = [
                  'hash' => [
                'column' => 'hash',
                'format' => 'string'
            ],
                 'width' => [
                'column' => 'width',
                'format' => 'string'
            ],
                'height' => [
                'column' => 'height',
                'format' => 'string'
            ],
                  'path' => [
                'column' => 'path',
                'format' => 'pre',
                  'data' => getenv('WEB_URL') . '/'
            ],
                  'size' => [
                'column' => 'size',
                'format' => 'size'
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