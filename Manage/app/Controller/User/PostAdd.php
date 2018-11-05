<?php

namespace Dolphin\Ting\Controller\User;

class PostAdd extends \Dolphin\Ting\Controller\Base
{
    public function __invoke($request, $response, $args)
    {   
        $body = $request->getParsedBody();

        $avatar = [
            '',
            'assets/images/faces/male/1.jpg',
            'assets/images/faces/male/2.jpg',
            'assets/images/faces/male/3.jpg',
            'assets/images/faces/male/4.jpg',
            'assets/images/faces/male/5.jpg',
            'assets/images/faces/male/6.jpg',
            'assets/images/faces/male/7.jpg',
            'assets/images/faces/male/8.jpg',
            'assets/images/faces/female/1.jpg',
            'assets/images/faces/female/2.jpg',
            'assets/images/faces/female/3.jpg',
            'assets/images/faces/female/4.jpg',
            'assets/images/faces/female/5.jpg',
            'assets/images/faces/female/6.jpg',
            'assets/images/faces/female/7.jpg',
            'assets/images/faces/female/8.jpg'
        ];

        $data = [
                'uuid' => $this->random_code(),
            'username' => trim($body['username']),
                'name' => trim($body['name']),
            'nickname' => trim($body['nickname']),
               'phone' => trim($body['phone']),
               'email' => trim($body['email']),
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
              'avatar' => $avatar[rand(1, 16)],
                'sign' => trim($body['sign']),
               'group' => $body['group']
        ];

        $db = $this->app->db->insert("user", $data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加用户失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加用户成功'
            ]);  
        }

        return $response->withRedirect('/user/records', 302);
        
    }
}