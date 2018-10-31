<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Dolphin\Ting\Model\Collection_model;

class PostUpload extends Pic
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
    protected $collection_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->image_opt    = OptimizerChainFactory::create();

        $this->image_hash   = new ImageHash(new DifferenceHash());

        $this->is_debug     = getenv("DEBUG") === "TRUE" ? true : false;

        $this->uuid         = $app->session->get('uuid');

        $this->collection_model = new Collection_model($app);
    }

    public function __invoke($request, $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (isset($querys['categroy'])) { // 分类
            $categroy_code = $querys['categroy'];
        }

        if (isset($querys['collection'])) { // 专题
            $collection_code = $querys['collection'];
        }

        $upload_files = $request->getUploadedFiles();

        $upload_file  = $upload_files['photo'];

        if ($upload_file->getError() === UPLOAD_ERR_OK) {
            $data = $this->move($upload_file);

            if (! empty($data)) { // 本地上传成功
                // 插入数据库
                $data['uuid']          = $this->uuid;
                $data['is_oss']        = 0;
                $data['categroy_code'] = isset($categroy_code) ? $categroy_code : '';
          
                $db = $this->common_model->add($data);

                if ($db->rowCount() && isset($collection_code)) {
                    $this->collection_model->add_picture($collection_code, $data['hash']);
                }
            }
        }

        return $response->withJson([
             'name' => $request->getAttribute('next_name'),
            'value' => $request->getAttribute('next_value')
        ]);
    }

    private function move($file)
    {
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $file_size = $file->getSize();
        $temp_name = md5($file->getClientFilename()) . '.' . $extension;
        $temp_path = Common::PHOTO_DIR . '/' . Common::PHOTO_DIR_TEMP . '/' . $temp_name;
        // 先将文件移入临时目录
        $file->moveTo($temp_path);
        // 获取文件的 Hash 值
        $hash = $this->image_hash->hash($temp_path);
        
        $is_success = false;
        // 判断是否存在
        if (! $this->common_model->is_has('hash', $hash)) {
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