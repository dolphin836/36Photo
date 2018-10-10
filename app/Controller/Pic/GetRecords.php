<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Model\Common_model;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class GetRecords extends Pic
{
    private $common_model;

    private $columns = [
        '缩略图',
        '宽',
        '高',
        '大小',
        '创建时间'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->common_model = new Common_model($app, $this->table_name);
    }

    public function __invoke($request, $response, $args)
    {  
        $page = $request->getAttribute('page');

        $records = $this->common_model->records([
            "LIMIT" => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ]);

        $images = [];

        foreach ($records as $record) {
            if ($record['is_oss']) {
                $valid = 3600;

                try {
                    $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET", [
                        OssClient::OSS_PROCESS => "image/resize,m_fill,h_80,w_80"
                    ]);
                } catch (OssException $e) {
                    $path = getenv('WEB_URL') . '/' . $record['path'];
                }
            } else {
                $path = getenv('WEB_URL') . '/' .$record['path'];
            }
            
            $images[] = [
                     'hash' => $record['hash'],
                    'width' => $record['width'],
                   'height' => $record['height'],
                     'size' => $this->size($record['size']),
               'gmt_create' => $record['gmt_create'],
                     'path' => $path,
                   'is_oss' => $record['is_oss'] ? true : false
            ];
        }

        $data = [
            "records" => $images,
            "columns" => $this->columns,
               "page" => Page::reder('/pic/records', $this->common_model->total(), $page, Common::PAGE_COUNT, '')
        ];

        $this->respond('Pic/Records', $data);
    }

    private function size($size)
    {
        $kb = ceil($size / 1024);

        if ($kb < 1024) {
            return $kb . ' KB';
        }

        $mb = round($kb / 1024, 2);

        return $mb . ' M';
    }
}