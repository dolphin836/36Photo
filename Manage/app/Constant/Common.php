<?php

namespace Dolphin\Ting\Constant;

/**
 * 通用
 */

class Common
{
    // 分页数量
    const PAGE_COUNT = 20;
    // 图片存储根目录
    const PHOTO_DIR = 'uploads';
    // 图片上传临时存储目录
    const PHOTO_DIR_TEMP = 'temp';
    // 从图片中提取的颜色数量
    const COLOR_COUNT = 5;
    // 从图片中提取颜色时的计算精度
    const COLOR_QUALITY = 10;
    // 已上传阿里云 OSS
    const IS_OSS = '1';
    // 未上传阿里云 OSS
    const IS_NOT_OSS = '0';
    // 图片列表的略缩图宽度
    const PHOTO_LIST_THUMB = 400;
}
