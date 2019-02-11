<?php

namespace Dolphin\Ting\Librarie;

use Intervention\Image\ImageManagerStatic as Image;
use Dolphin\Ting\Constant\Common;
// 图像处理
class Photo
{
    // 获取略缩图
    public static function thumb ($path)
    {
        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . Common::PHOTO_LIST_THUMB . 'x' . Common::PHOTO_LIST_THUMB . '.' . $info['extension'];

        if (! file_exists($thumb)) {
            $image = Image::make($path);

            $image->fit(Common::PHOTO_LIST_THUMB);

            $image->save($thumb);
        }

        return getenv('WEB_URL') . '/' . $thumb;
    }
}

