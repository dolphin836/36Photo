<?php

namespace Dolphin\Ting\Controller\Pic;

use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class Record extends Pic
{
    public function __invoke(Request $request, Response $response, $args)
    { 
        $hash = $args['hash'];

        $record = $this->pic_model->record(["hash" => $hash]);
        // 生产环境
        if (is_null($record) || (getenv('DEBUG') === 'FALSE' && $record['is_oss'] === Common::IS_NOT_OSS)) {
            throw new NotFoundException($request, $response);
        }

        if ($record['is_oss'] === Common::IS_OSS) {
            $valid = Common::OSS_VALID;

            try {
                $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET");
            } catch (OssException $e) {
                $path = getenv('WEB_URL') . '/' . $record['path'];
            }
        } else {
            $path = getenv('WEB_URL') . '/' . $record['path'];
        }
        // 颜色
        $color = $this->pic_model->pic_color($hash);
        // 标签
        $mark  = $this->pic_model->pic_mark($hash);

        $data = [
                     'hash' => $record['hash'],
                    'width' => $record['width'],
                   'height' => $record['height'],
                     'size' => $this->size($record['size']),
               'gmt_create' => $record['gmt_create'],
                    'photo' => $path,
            'categroy_code' => $record['code'],
            'categroy_name' => $record['name'],
                     'uuid' => $record['uuid'],
                 'username' => $record['username'],
                    'color' => array_column($color, 'color'),
                     'mark' => $mark
        ];

        $this->respond('Pic/Record', $data);
    }
}