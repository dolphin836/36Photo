<?php

namespace Dolphin\Ting\Controller\Color;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Model\Category_model;
use Dolphin\Ting\Model\Mark_model;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Slim\Exception\NotFoundException;
use Dolphin\Ting\Librarie\Photo;

class GetPic extends Color
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

    private $category_model;

    private $pic_model;

    private $mark_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav       = Nav::PICTURE;
        $this->nav_route = Nav::RECORDS;

        $this->pic_model      = new Pic_model($app);
        $this->category_model = new Category_model($app);
        $this->mark_model     = new Mark_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        // 分页
        $page   = $request->getAttribute('page');
        // 检索
        $search = $request->getAttribute('search');
        $text   = $request->getAttribute('text');
        // 排序
        $sort   = $request->getAttribute('sort');
        $order  = $request->getAttribute('order');

        $uri    = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (! isset($querys['color'])) {
            throw new NotFoundException($request, $response);
        }
        // 颜色
        $color = $querys['color'];

        $search['color'] = $color;
        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];
        $search['ORDER'] = [Table::PICTURE_COLOR . '.id' => 'DESC'];

        $query = '&color=' . $color;

        if (! empty($text)) {
            $query .= '&' . http_build_query($text);
        }

        if ($sort != '') {
            $search['ORDER'] = [Table::PICTURE . "." . $sort => $order];
            $query .= '&sort='  . $sort;
            $query .= '&order=' . $order;
        }

        $records = $this->color_model->pic($search);

        $hashs   = array_column($records, 'picture_hash');

        $images  = [];

        foreach ($hashs as $hash) {
            $record = $this->pic_model->record(["hash" => $hash]);

            if ($record['is_oss']) {
                $valid = 3600;

                try {
                    $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET", [
                        OssClient::OSS_PROCESS => "image/resize,m_fill,h_" . Common::PHOTO_LIST_THUMB . ",w_" . Common::PHOTO_LIST_THUMB
                    ]);
                } catch (OssException $e) {
                    $path = Photo::thumb($record['path']);
                }
            } else {
                $path = Photo::thumb($record['path']);
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
            "sort_item" => $this->sort_item,
                 "sort" => $sort,
                "order" => $order,
                 "text" => $search,
                 "page" => Page::reder('/mark/pic', $this->color_model->pic_total($search), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Pic/Records', $data);
    }
}