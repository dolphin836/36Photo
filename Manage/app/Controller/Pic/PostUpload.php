<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Common;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Dolphin\Ting\Model\Collection_model;
use Dolphin\Ting\Model\Pic_model;

class PostUpload
{
    // 图片优化
    private $image_opt;
    // 图片 Hash
    private $image_hash;
    // 运行环境
    private $is_debug;
    // 
    private $uuid;
    // 专题
    private $collection_model;
    //
    private $pic_model;

    private $error = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
        9 => 'The photo is already exist.'
    ];

    function __construct(ContainerInterface $app)
    {
        $this->image_opt        = OptimizerChainFactory::create();

        $this->image_hash       = new ImageHash(new DifferenceHash());

        $this->is_debug         = getenv("DEBUG") === "TRUE" ? true : false;

        $this->uuid             = $app->session->get('uuid');

        $this->collection_model = new Collection_model($app);

        $this->pic_model        = new Pic_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (isset($querys['category'])) { // 分类
            $category_code = $querys['category'];
        }

        if (isset($querys['collection'])) { // 专题
            $collection_code = $querys['collection'];
        }

        $upload_files = $request->getUploadedFiles();

        $upload_file  = $upload_files['photo'];

        $upload_code  = $upload_file->getError();

        if ($upload_code === UPLOAD_ERR_OK) {
            $data = $this->move($upload_file);

            if (! empty($data)) { // 本地上传成功
                // 插入数据库
                $data['uuid']          = $this->uuid;
                $data['is_oss']        = Common::IS_NOT_OSS;
                $data['category_code'] = isset($category_code) ? $category_code : '';

                $db = $this->pic_model->add($data);
                // 专题
                if ($db->rowCount() && isset($collection_code)) {
                    $this->collection_model->add_picture($collection_code, $data['hash']);
                }
            } else {
                // 已经存在
                $upload_code = 9;
            }
        }

        return $response->withJson([
            'code' => $upload_code,
            'note' => $this->error[$upload_code]
        ]);
    }

    private function move($file)
    {
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $temp_name = md5($file->getClientFilename()) . '.' . $extension;
        $temp_path = Common::PHOTO_DIR . '/' . Common::PHOTO_DIR_TEMP . '/' . $temp_name;
        // 先将文件移入临时目录
        $file->moveTo($temp_path);
        // 获取文件的 Hash 值
        $hash = $this->image_hash->hash($temp_path);
        
        $is_success = false;
        // 判断是否存在
        if (! $this->pic_model->is_has('hash', $hash)) {
            $now = time();

            $y   = date("Y", $now);
            $m   = date("m", $now);
            $d   = date("d", $now);
          
            $dir = Common::PHOTO_DIR . '/' . $y . '/' . $m . '/' . $d;

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file_name = md5_file($temp_path) . '.' . $extension;
            $file_path = $dir . '/' . $file_name;
    
            $this->image_opt->optimize($temp_path, $file_path);

            $file_size = filesize($file_path);

            $is_success = true;
        }
        // 删除临时文件
        unlink($temp_path);

        $data = [];

        if ($is_success) {
            list($width, $height, $type, $attr) = getimagesize($file_path);

            $data = [
                  'hash' => $hash,
                  'size' => $file_size,
                  'path' => $file_path,
                 'width' => $width,
                'height' => $height
            ];
        }

        return $data;
    }

}