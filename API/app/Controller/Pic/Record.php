<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class Record extends Pic
{
    public function __invoke($request, $response, $args)
    { 
        $hash = $args['hash'];

        $record = $this->pic_model->record(["hash" => $hash]);

        if (is_null($record)) {
            return $response->withJson($this->respond(Common::ERROR_CODE_DATA));
        }

        if ($record['is_oss']) {
            $valid = Common::OSS_VALID;

            try {
                $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET");
            } catch (OssException $e) {
                $path = getenv('WEB_URL') . '/' . $record['path'];
            }
        } else {
            $path = getenv('WEB_URL') . '/' .$record['path'];
        }

        $data = [
                     'hash' => $record['hash'],
                    'width' => $record['width'],
                   'height' => $record['height'],
                     'size' => $this->size($record['size']),
               'gmt_create' => $record['gmt_create'],
                     'path' => $path,
                   'is_oss' => $record['is_oss'] ? '1' : '0',
            'categroy_code' => $record['code'],
            'categroy_name' => $record['name'],
                     'uuid' => $record['uuid'],
                 'username' => $record['username']
        ];

        return $response->withJson($this->respond(Common::ERROR_CODE_SUCCESS, $data));
    }
}