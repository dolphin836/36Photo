<?php

/**
 * 压缩图片测试
 * 
 * @author whb
 * @create 2018-12-29 22:10:00
 * @update 2018-12-29 22:10:00
 */

use Spatie\ImageOptimizer\OptimizerChainFactory;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';

$dir = ROOTPATH . 'public/picture';

$results = new \FilesystemIterator($dir);

$i = 1;

foreach ($results as $result) {
    // 过滤
    if (! $result->isFile()) continue;

    $origin_photo = $result->getPathname();

    var_dump($origin_photo);
    // 获取原始文件大小
    $origin_photo_size = filesize($origin_photo);

    var_dump($origin_photo_size);

    // 图片优化
    $compress_photo = $dir . '/temp/' . $i . '.jpg';

    $image_opt = OptimizerChainFactory::create();

    $image_opt->optimize($origin_photo, $compress_photo);

    // 获取压缩后文件大小
    $compress_photo_size = filesize($compress_photo);

    var_dump($compress_photo_size);

    $i++;
}

