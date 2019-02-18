<?php

namespace Dolphin\Ting\Librarie;

use Intervention\Image\ImageManagerStatic as Image;
use Dolphin\Ting\Constant\Common;
// 图像处理
class Photo
{
    // 前台图片列表页的图片宽度
    const PHOTO_WIDTH     = 560;
    // 前台每日推荐页的图片宽度
    const RECOMMEND_WIDTH = 280;
    // 生成略缩图
    public static function thumb ($path)
    {
        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        // 后台图片列表略缩图
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . Common::PHOTO_LIST_THUMB . 'x' . Common::PHOTO_LIST_THUMB . '.' . $info['extension'];

        if (! file_exists($thumb)) {
            $image = Image::make($path);

            $image->fit(Common::PHOTO_LIST_THUMB);

            $image->save($thumb);
        }
        // 前台图片列表页略缩图
        $photo = $info['dirname'] . '/' . $info['filename'] . '_' . self::PHOTO_WIDTH . '.' . $info['extension'];

        if (! file_exists($photo)) {
            $image = Image::make($path);

            $image->resize(self::PHOTO_WIDTH, NULL, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($photo);
        }
        // 前台每日推荐页略缩图
        $recommend = $info['dirname'] . '/' . $info['filename'] . '_' . self::RECOMMEND_WIDTH . '.' . $info['extension'];

        if (! file_exists($recommend)) {
            $image = Image::make($path);

            $image->fit(self::RECOMMEND_WIDTH);

            $image->save($recommend);
        }
    }
    // 获取后台图片列表略缩图的路径
    public static function get_thumb_path ($path)
    {
        $info  = pathinfo($path);
        // 略缩图和源图位于同一路径
        // 后台图片列表略缩图
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_' . Common::PHOTO_LIST_THUMB . 'x' . Common::PHOTO_LIST_THUMB . '.' . $info['extension'];

        return getenv('WEB_URL') . '/' . $thumb;
    }
}

