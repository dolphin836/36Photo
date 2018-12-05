<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Model\Category_model;
use Dolphin\Ting\Model\Collection_model;

class GetAdd extends Pic
{
    protected $category_model;

    protected $collection_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->category_model   = new Category_model($app);

        $this->collection_model = new Collection_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $data = [];

        if (isset($querys['category']) && $this->category_model->is_has("code", $querys['category'])) { // 分类
            $record = $this->category_model->record(['code' => $querys['category']]);

            $data['category'] = [
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