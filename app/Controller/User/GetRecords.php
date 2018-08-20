<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page as Page;

class GetRecords extends \Dolphin\Ting\Controller\Base
{
    public function __invoke($request, $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $query_str);

        $page = isset($query_str['page']) && $query_str['page'] > 0 ? (int) $query_str['page'] : 1;

        $order = isset($query_str['order']) && in_array($query_str['order'], ['ASC', 'DESC']) ? $query_str['order'] : 'DESC';
        // CSRF
        $this->is_csrf = false;

        $total = $this->app->db->count("user");

        $records = $this->app->db->select("user", [
            "uuid",
            "recommend_uuid",
            "username",
            "name",
            "nickname",
            "phone",
            "email",
            "avatar",
            "open_id",
            "client",
            "group",
            "last_login"
        ], [
            "ORDER" => ["id" => $order],
            "LIMIT" => [$this->count * ($page - 1), $this->count]
        ]);

        // if (empty($records)) {
        //     return $response->withHeader('Location', '/404');
        // }

        $data = [];

        foreach ($records as $user) {
            $data['records'][] = [
                      "uuid" => $user["uuid"],
                  "username" => $user["username"],
                      "name" => $user["name"],
                  "nickname" => $user["nickname"],
                     "phone" => $user["phone"],
                     "email" => $user["email"],
                    "avatar" => '/' . $user["avatar"],
                 "is_wechat" => $user['open_id'] ? true : false,
                    "client" => $user["client"],
               "client_name" => $this->user_client[$user["client"]],
                     "group" => $user["group"],
                "group_name" => $this->user_groups[$user["group"]],
                "last_login" => $user["last_login"] ? date("Y-m-d H:i:s", $user["last_login"]) : ''
            ];
        }

        $data['class'] = ['blue', 'azure', 'indigo', 'purple', 'pink', 'orange'];

        $data['page'] = Page::reder('/user/records', $total, $page, $this->count);

        $this->respond('User\Records.html', $data);
    }
}