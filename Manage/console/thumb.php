<?php

/**
 * 生成本地图片的缩略图
 * 
 * @author whb
 * @create 2019-02-18 15:40:00
 * @update 2019-02-18 15:40:00
 */

use Dolphin\Ting\Librarie\Photo;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
// 设置临时最大内存
ini_set('memory_limit', '1024M');
// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');
// 扫描图片存储目录

found(ROOTPATH . '/public/uploads');

function found ($dir)
{
    $dir_arr = explode ('/public/uploads', $dir, 2);

    if ($dir_arr[1] !== '')  {
        var_dump(date("Y-m-d H:i:s") . ':开始处理目录:' . $dir_arr[1]);
    }

    $results = new \FilesystemIterator($dir);

    foreach ($results as $result) {
        // 递归目录
        if ($result->isDir()) {
            found($result->getPathname());
        }

        // 过滤
        if (! $result->isFile()) continue;

        $path = $result->getPathname();
        // 不是原始图片，不处理
        $is_origin_photo = strpos($path, '_');

        if ($is_origin_photo !== false) {
            continue;
        }

        $info = pathinfo($path);

        var_dump(date("Y-m-d H:i:s") . ':图片:' . $info['basename']);
        // 生成缩略图
        Photo::thumb($path); // 后台图片列表
        var_dump(date("Y-m-d H:i:s") . ':缩略图生成完成.');
    }
}
