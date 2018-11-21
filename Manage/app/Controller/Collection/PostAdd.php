<?php

namespace Dolphin\Ting\Controller\Collection;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class PostAdd extends Collection
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $body = $request->getParsedBody();

        if (! $this->validate($body)) {
            return $response->withRedirect('/collection/add', 302);
        }

        $data = [
                 'code' => $this->random_code(),
                 'uuid' => $this->app->session->get('uuid'),
                 'name' => trim($body['name']),
              'content' => trim($body['content']),
            'is_public' => (int) $body['is_public'],
            'link_name' => trim($body['link_name']),
                 'link' => trim($body['link'])
        ];

        $db = $this->collection_model->add($data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加专题失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加专题成功'
            ]);  
        }

        return $response->withRedirect('/collection/records', 302);
    }

    private function validate($body)
    {
        return true;
    }
}