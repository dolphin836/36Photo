<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;
use Slim\Exception\NotFoundException;

class Record extends Pic
{
    public function __invoke($request, $response, $args)
    { 
        $hash = $args['hash'];

        $record = $this->pic_model->record(["hash" => $hash]);
        // 生产环境
        if (is_null($record) || (getenv('DEBUG') === 'FALSE' && $record['is_oss'] === 0)) {
            throw new NotFoundException($request, $response);
        }

        if ($record['is_oss'] === 1) {
            $valid = Common::OSS_VALID;

            try {
                $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET");
            } catch (OssException $e) {
                $path = getenv('WEB_URL') . '/' . $record['path'];
            }
        } else {
            $path = getenv('WEB_URL') . '/' . $record['path'];
        }

        $data = [
                     'hash' => $record['hash'],
                    'width' => $record['width'],
                   'height' => $record['height'],
                     'size' => $this->size($record['size']),
               'gmt_create' => $record['gmt_create'],
                     'path' => $path,
            'categroy_code' => $record['code'],
            'categroy_name' => $record['name'],
                     'uuid' => $record['uuid'],
                 'username' => $record['username']
        ];

        $this->respond('Pic/Record', $data);
    }
}