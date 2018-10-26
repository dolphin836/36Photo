<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Dolphin\Ting\Model\Common_model;

class PostUpload extends Pic
{
    // 图片优化
    private $image_opt;
    // 图片 Hash
    private $image_hash;
    //
    private $common_model;
    // 运行环境
    private $is_debug;
    // 
    private $uuid;
    //
    private $note = [
        '上传成功.',
        '上传失败.',
        '已经存在.',
        '保存失败.'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->image_opt    = OptimizerChainFactory::create();

        $this->image_hash   = new ImageHash(new DifferenceHash());

        $this->common_model = new Common_model($app, $this->table_name);

        $this->is_debug     = getenv("DEBUG") === "TRUE" ? true : false;

        $this->uuid         = $app->session->get('uuid');
    }

    public function __invoke($request, $response, $args)
    {  
        $upload_files = $request->getUploadedFiles();

        $upload_file  = $upload_files['file'];

        if ($upload_file->getError() === UPLOAD_ERR_OK) {
            $data = $this->move($upload_file);

            if (! empty($data)) { // 本地上传成功
                // 插入数据库
                $data['uuid']   = $this->uuid;
                $data['is_oss'] = 0;
          
                $query = $this->common_model->add($data);

                if ($query->rowCount()) {
                    $code = 0;
                } else {
                    $code = 3;
                }
            } else {
                $code = 2;
            }
        } else {
            $code = 1;
        }

        return $response->withJson([
            "code" => $code,
            "note" => $this->note[$code]
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