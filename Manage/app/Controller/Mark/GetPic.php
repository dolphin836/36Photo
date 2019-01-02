<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Model\Category_model;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class GetPic extends Mark
{
    private $columns = [
        '缩略图',
        '分类',
        '标签',
        '所有者',
        '大小',
        '创建时间'
    ];

    private $category_model;

    private $pic_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav       = Nav::PICTURE;
        $this->nav_route = Nav::RECORDS;

        $this->pic_model = new Pic_model($app);
        $this->category_model = new Category_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        // 分页
        $page = $request->getAttribute('page');

        $uri  = $request->getUri();

        parse_str($uri->getQuery(), $querys);
        // 标签 ID
        $mark_id = $querys['mark'];

        $query   = '&mark=' . $mark_id;

        $search  = [
            'mark_id' => $mark_id,
              'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT],
              'ORDER' => ['id' => 'DESC']
        ];

        $records = $this->mark_model->pic($search);

        $hashs = array_column($records, 'picture_hash');

        $images  = [];

        foreach ($hashs as $hash) {
            $record = $this->pic_model->record(["hash" => $hash]);

            if ($record['is_oss']) {
                $valid = 3600;

                try {
                    $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET", [
                        OssClient::OSS_PROCESS => "image/resize,m_fill,h_400,w_400"
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
                'category_code' => $record['code'],
                'category_name' => $record['name'],
                         'uuid' => $record['uuid'],
                     'username' => $record['username'],
                         'mark' => $this->mark_model->pic_mark($record['hash'])
            ];
        }

        $data = [
             "records" => $images,
             "columns" => $this->columns,
            "category" => $this->category_model->records(),
                "page" => Page::reder('/mark/pic', $this->mark_model->pic_total($mark_id), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Pic/Records', $data);
    }
}