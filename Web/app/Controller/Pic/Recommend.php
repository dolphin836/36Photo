<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Recommend_model;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;

class Recommend extends Pic
{
    private $recommend_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->recommend_model = new Recommend_model($app);

        $this->nav = Nav::RECOMMEND;
    }
    /**
     * 每日推荐
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $day = isset($args['day']) && strtotime($args['day']) < time() ? $args['day'] : date('Ymd');

        if (! strtotime($day)) {
            throw new NotFoundException($request, $response);
        }
        // 每日推荐
        $records = $this->recommend_model->records([
            'gmt_create[>]' => date("Y-m-d H:i:s", strtotime($day)),
            'gmt_create[<]' => date("Y-m-d H:i:s", strtotime('+1 day', strtotime($day))),
            "ORDER" => ["id" => "DESC"],
            "LIMIT" => [0, Common::RECOMMEND_DAY_MAX]
        ]);
        // 每日推荐的图片 Hash 数组
        $hash = array_column($records, 'picture_hash');
        // 每日推荐的图片记录
        $recommend = $this->pic_model->records(['hash' => $hash]);
        // 最新推荐
        $fifter  = [
            "ORDER" => ["id" => "DESC"],
            "LIMIT" => [0, Common::RECOMMEND_NEW_MAX]
        ];

        if (! empty($hash)) { // 排除每日推荐中已经存在的记录
            $fifter["picture_hash[!]"] = $hash;
        }

        $records = $this->recommend_model->records($fifter);

        $photos = $this->pic_model->records(['hash' => array_column($records, 'picture_hash')]);

        $data = [
                  'day' => date("Y-m-d", strtotime($day)),
                'today' => date("Ymd"),
             'tomorrow' => date("Ymd", strtotime('+1 day', strtotime($day))),
            'yesterday' => date("Ymd", strtotime('-1 day', strtotime($day))),
            'recommend' => $this->convert($recommend, 'RECOMMEND'),
                'photo' => $this->convert($photos, 'RECOMMEND'),
        ];

        $this->respond('Recommend/Record', $data);
    }
}
