<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class Records extends Pic
{
    public function __invoke($request, $response, $args)
    { 
        $page    = isset($args['page']) ? $args['page'] : 1;

        $records = $this->pic_model->records([
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ]);

        if (empty($records)) {
            return $response->withJson($this->respond(Common::ERROR_CODE_DATA));
        }

        $data    = [];

        foreach ($records as $record) {
            if ($record['is_oss']) {
                $valid = Common::OSS_VALID;

                try {
                    $path = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => Common::OSS_PROCESS
                        ]
                    );
                } catch (OssException $e) {
                    $path = getenv('WEB_URL') . '/' . $record['path'];
                }
            } else {
                $path = getenv('WEB_URL') . '/' .$record['path'];
            }

            $data[] = [
                'hash' => $record['hash'],
                'path' => $path
            ];
        }

        return $response->withJson($this->respond(Common::ERROR_CODE_SUCCESS, $data));
    }
}