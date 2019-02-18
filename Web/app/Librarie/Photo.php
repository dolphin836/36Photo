<?php

namespace Dolphin\Ting\Librarie;

use Intervention\Image\ImageManagerStatic as Image;
use Dolphin\Ting\Constant\Common;
// 图像处理
class Photo
{
    // 图片存储的相对路径
    const PHOTO_SAVE_PATH = '../../Manage/public/';

    // 保持宽高比缩放
    public static function resize ($path, $width)
    {
        $path  = self::PHOTO_SAVE_PATH . $path;

        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . $width . '.' . $info['extension'];

        if (! file_exists($thumb)) {
            $image = Image::make($path);

            $image->resize($width, NULL, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($thumb);
        }

        return getenv('WEB_URL') . '/' . str_replace(self::PHOTO_SAVE_PATH, '', $thumb);
    }

    // 等比例缩放 + 裁剪
    public static function fit ($path, $width)
    {
        $path  = self::PHOTO_SAVE_PATH . $path;

        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . $width . '.' . $info['extension'];

        if (! file_exists($thumb)) {
            $image = Image::make($path);

            $image->fit($width);

            $image->save($thumb);
        }

        return getenv('WEB_URL') . '/' . str_replace(self::PHOTO_SAVE_PATH, '', $thumb);
    }
}

