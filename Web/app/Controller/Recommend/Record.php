<?php

namespace Dolphin\Ting\Controller\Recommend;

use Dolphin\Ting\Constant\Common;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;

class Record extends Recommend
{
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
        $photos = $this->pic_model->records(['hash' => $hash]);

        // 最新推荐
        $records = $this->recommend_model->records([
            "picture_hash[!]" => $hash, // 排除每日推荐中已经存在的记录
                      "ORDER" => ["id" => "DESC"],
                      "LIMIT" => [0, Common::RECOMMEND_NEW_MAX]
        ]);

        $photos = $this->pic_model->records(['hash' => array_column($records, 'picture_hash')]);

        // $this->respond('Recommend/Record', $data);
    }

    private function convert($records)
    {
        $photos = [];

        $sma   = $lar = Common::OSS_PROCESS;
        $sma  .= Common::RECOMMEND_RESIZE_SMA;
        $lar  .= Common::RECOMMEND_RESIZE_LAR;

        if ($this->is_support_webp) {
            $sma .= Common::OSS_PROCESS_FORMAT;
            $lar .= Common::OSS_PROCESS_FORMAT; 
        }

        foreach ($records as $record) {
            if ($record['is_oss'] === Common::IS_OSS) {
                $valid = Common::OSS_VALID;

                try {
                    $small = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => $sma
                        ]
                    );

                    $large = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => $lar
                        ]
                    );
                } catch (OssException $e) {
                    $large = $small = getenv('WEB_URL') . '/' . $record['path'];
                }
            } else {
                $large = $small = getenv('WEB_URL') . '/' .$record['path'];
            }

            $photos[] = [
                 'hash' => $record['hash'],
                'large' => $large,
                'small' => $small
            ];
        }

        return $photos;
    }
}
