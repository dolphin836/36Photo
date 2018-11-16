<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient;
use OSS\Core\OssException;

class Records extends Pic
{
    public function __invoke($request, $response, $args)
    { 
        $page    = isset($args['page']) ? $args['page'] : 1;

        $fifter  = [
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ]; 

        // 生产环境展示已上传阿里云的图片
        if (getenv('DEBUG') === 'FALSE') {
            $fifter[Table::PICTURE . '.is_oss'] = 1;
        }

        $records = $this->pic_model->records($fifter);

        $photos  = [];

        foreach ($records as $record) {
            if ($record['is_oss']) {
                $valid = Common::OSS_VALID;

                try {
                    $small = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => Common::OSS_PROCESS_RESIZE
                        ]
                    );

                    if ($this->is_support_webp) {
                        $full = $this->oss_client->signUrl(
                            getenv('OSS_BUCKET_NAME'),
                            $record['path'],
                            $valid,
                            "GET",
                            [
                                OssClient::OSS_PROCESS => Common::OSS_PROCESS_FORMAT
                            ]
                        );
                    } else {
                        $full = $this->oss_client->signUrl(
                            getenv('OSS_BUCKET_NAME'),
                            $record['path'],
                            $valid,
                            "GET"
                        );
                    }
                } catch (OssException $e) {
                    $full = $small = getenv('WEB_URL') . '/' . $record['path'];
                }
            } else {
                $full = $small = getenv('WEB_URL') . '/' .$record['path'];
            }

            $photos[] = [
                  'hash' => $record['hash'],
                  'full' => $full,
                 'small' => $small,
                 'width' => $record['width'],
                'height' => $record['height']
            ];
        }

        $data = [
            'photos' => $photos
        ];

        $this->respond('Pic/Records', $data);
    }
}
