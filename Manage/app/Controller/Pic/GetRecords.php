<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;
use OSS\OssClient;
use OSS\Core\OssException;
use Dolphin\Ting\Model\Mark_model;
use Dolphin\Ting\Model\Category_model;
use Dolphin\Ting\Librarie\Photo;

class GetRecords extends Pic
{
    private $columns = [
        '缩略图',
        '分类',
        '标签',
        '所有者',
        '大小',
        '创建时间'
    ];
    //
    private $sort_item = [
        'gmt_create' => '日期',
        'size'       => '大小',
        'width'      => '宽',
        'height'     => '高',
        'browse'     => '浏览量',
        'download'   => '下载量',
        'collect'    => '收藏量'
    ];

    private $mark_model;

    private $category_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->mark_model     = new Mark_model($app);
        $this->category_model = new Category_model($app);

        $this->nav_route = Nav::RECORDS;
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        // 分页
        $page   = $request->getAttribute('page');
        // 检索
        $search = $request->getAttribute('search');

        $text   = $request->getAttribute('text');

        $query  = '';

        if (! empty($text)) {
            $query .= '&';
            $query .= http_build_query($text);
        }

        $sort  = $request->getAttribute('sort');
        $order = $request->getAttribute('order');

        if ($sort != '') {
            $search['ORDER'] = [Table::PICTURE . "." . $sort => $order];
            $query .= '&sort='  . $sort;
            $query .= '&order=' . $order;
        }

        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];

        $records = $this->pic_model->records($search);

        $images  = [];

        foreach ($records as $record) {
            if ($record['is_oss']) {
                $valid = 3600;

                try {
                    $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET", [
                        OssClient::OSS_PROCESS => "image/resize,m_fill,h_" . Common::PHOTO_LIST_THUMB . ",w_" . Common::PHOTO_LIST_THUMB
                    ]);
                } catch (OssException $e) {
                    $path = Photo::get_thumb_path($record['path']);
                }
            } else {
                $path = Photo::get_thumb_path($record['path']);
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
            "sort_item" => $this->sort_item,
                 "sort" => $sort,
                "order" => $order,
                 "text" => $search,
             "category" => $this->category_model->records(),
                 "page" => Page::reder('/pic/records', $this->pic_model->total($search), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Pic/Records', $data);
    }
}