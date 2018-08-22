<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page as Page;

class GetRecords extends \Dolphin\Ting\Controller\Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = 'user';
    }

    public function __invoke($request, $response, $args)
    {  
        // CSRF
        $this->is_csrf = false;

        $where = [];

        if (! empty($request->getAttribute('filter'))) {
            foreach ($request->getAttribute('filter') as $key => $value) {
                $where[substr($key, 7) . '[~]'] = $value;
            }
        }

        $where["ORDER"] = ["id" => $request->getAttribute('order')];
        $where["LIMIT"] = [$this->count * ($request->getAttribute('page') - 1), $this->count];

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
        ], $where);

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

        $data['page'] = Page::reder('/user/records', $this->total(), $request->getAttribute('page'), $this->count);

        $data['filter'] = $request->getAttribute('filter');

        $this->respond('User\Records.html', $data);
    }
}