<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use OSS\OSSClient;
use OSS\Core\OSSException;

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
    // oss client
    private $oss_client;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        // 设置临时超时时间
        set_time_limit(0);
        // 设置临时最大内存
        ini_set('memory_limit', '512M');

        $this->table_name = "picture";

        $this->image_hash = new ImageHash(new DifferenceHash());

        try {
            $this->oss_client = new OssClient(getenv('OSS_ACCESS_KEY_ID'), getenv('OSS_ACCESS_SECRET'), getenv('OSS_END_POINT'));
        } catch (OssException $e) {
            printf(__FUNCTION__ . "阿里云 OSS 初始化失败。\n");
            printf($e->getMessage() . "\n");
            exit();
        } 
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

        return $response->withRedirect('/pic/records', 302);
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

            $is_oss = 1;

            try {
                $this->oss_client->uploadFile(getenv('OSS_BUCKET_NAME'), substr($upload, 2), $upload);
            } catch (OssException $e) {
                $is_oss = 0;
            }

            array_push($data, [
                'hash' => $hash,
                'uuid' => 'a22c38198f4b4ad992c4a1b89123d6e3',
               'width' => $width,
              'height' => $height,
                'path' => $upload,
                'size' => $size,
              'is_oss' => $is_oss
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