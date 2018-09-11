<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

/**
 * 自动导入功能
 */

class GetAuto extends \Dolphin\Ting\Controller\Base
{
    private $image_hash;
    // 需要遍历的目录
    private $dir = './picture';
    // 文件存储的目录
    private $upload = './uploads';
    // 
    private $hashs = [];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = "picture";

        $this->image_hash = new ImageHash(new DifferenceHash());
        // 设置临时超时时间
        set_time_limit(0);
        // 设置临时最大内存
        ini_set('memory_limit', '512M');
    }

    public function __invoke($request, $response, $args)
    {   
        $data = $this->found($this->dir);

        $db = $this->app->db->insert($this->table_name, $data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加图片失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加图片成功'
            ]);  
        }

        return $response->withStatus(302)->withHeader('Location', '/pic/records');
    }

    /**
     * 遍历文件夹，获取图片
     */
    private function found($dir = '')
    {
        static $data = [];

        if ($dir === '') {
            return $data;
        }

        $results = new \FilesystemIterator($dir);

        foreach($results as $result)
        {
            // 递归目录
            if ($result->isDir()) {
                $this->found($result->getPathname());
                // 删除目录
                rmdir($result->getPathname());
            }
            
            // 过滤
            if (! $result->isFile()) continue;

            $path = $result->getPathname();

            $hash = $this->image_hash->hash($path);
            // 已经存在
            if ( in_array($hash, $this->hashs) || $this->app->db->has($this->table_name, ['hash' => $hash])) {
                continue;
            }

            $size = $result->getSize();

            list($width, $height, $type, $attr) = getimagesize($path);
            // 移动到上传目录
            $upload = $this->upload($path, $result->getExtension());

            array_push($data, [
                'hash' => $hash,
                'uuid' => 'a22c38198f4b4ad992c4a1b89123d6e3',
               'width' => $width,
              'height' => $height,
                'path' => $upload,
                'size' => $size
            ]);
        }
        
        return $data;
    }

    /**
     * 移动到上传目录
     */
    private function upload($path, $extension)
    {
        $time = time();

        $y = date("Y", $time);
        $m = date("m", $time);
        $d = date("d", $time);

        $dir = $this->upload . '/' . $y . '/' . $m . '/' . $d;

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file_name = md5_file($path) . '.' . $extension;

        $file_path = $dir . '/' . $file_name;

        rename($path, $file_path);

        return $file_path;
    }
}