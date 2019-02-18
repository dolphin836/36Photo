<?php

namespace Dolphin\Ting\Librarie;

use Intervention\Image\ImageManagerStatic as Image;
use Dolphin\Ting\Constant\Common;
// 图像处理
class Photo
{
    // 图片存储的相对路径
    const PHOTO_SAVE_PATH = '../../Manage/public/';

    // 获取缩略图的文件存储路径
    public static function get_thumb_path ($path, $width)
    {
        $path  = self::PHOTO_SAVE_PATH . $path;

        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . $width . '.' . $info['extension'];

        return getenv('WEB_URL') . '/' . str_replace(self::PHOTO_SAVE_PATH, '', $thumb);
    }
}

