<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Model\Categroy_model;
use Dolphin\Ting\Model\Collection_model;

class GetAdd extends Pic
{
    protected $categroy_model;

    protected $collection_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->categroy_model   = new Categroy_model($app);

        $this->collection_model = new Collection_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $data = [
            'csrf' => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (isset($querys['categroy']) && $this->categroy_model->is_has("code", $querys['categroy'])) { // 分类
            $record = $this->categroy_model->record(['code' => $querys['categroy']]);

            $data['categroy'] = [
                'code' => $record['code'],
                'name' => $record['name']
            ];
        }

        if (isset($querys['collection']) && $this->collection_model->is_has("code", $querys['collection'])) { // 专题
            $record = $this->collection_model->record(['code' => $querys['collection']]);

            $data['collection'] = [
                'code' => $record['code'],
                'name' => $record['name']
            ];
        }
        
        $this->respond('Pic/Add', $data);
    }
}