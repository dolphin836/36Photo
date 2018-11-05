<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class GetRecords extends Pic
{
    private $columns = [
        '缩略图',
        '分类',
        '所有者',
        '大小',
        '创建时间'
    ];

    public function __invoke($request, $response, $args)
    {  
        // 分页
        $page   = $request->getAttribute('page');
        // 检索
        $search = $request->getAttribute('search');

        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];

        $records = $this->pic_model->records($search);

        $images  = [];

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
                       'is_oss' => $record['is_oss'] ? true : false,
                'categroy_code' => $record['code'],
                'categroy_name' => $record['name'],
                         'uuid' => $record['uuid'],
                     'username' => $record['username']
            ];
        }

        $data = [
            "records" => $images,
            "columns" => $this->columns,
               "page" => Page::reder('/pic/records', $this->pic_model->total($search), $page, Common::PAGE_COUNT, '')
        ];

        $this->respond('Pic/Records', $data);
    }
}