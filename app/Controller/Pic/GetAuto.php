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
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->table_name = "picture";
    }

    public function __invoke($request, $response, $args)
    {   
        $image_hash = new ImageHash(new DifferenceHash());

        $pictures = new \FilesystemIterator('./uploads');

        $data = [];

        foreach($pictures as $picture)
        {
            if (! $picture->isFile()) continue;

            $path = $picture->getPathname();

            $hash = $image_hash->hash($path);

            if ($this->app->db->has($this->table_name, ['hash' => $hash])) {
                continue;
            }

            list($width, $height, $type, $attr) = getimagesize($path);

            $data[] = [
                  'hash' => $hash,
                  'uuid' => 'a22c38198f4b4ad992c4a1b89123d6e3',
                 'width' => $width,
                'height' => $height,
                  'path' => $picture->getPathname(),
                  'size' => $picture->getSize()
            ];
        }

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
}