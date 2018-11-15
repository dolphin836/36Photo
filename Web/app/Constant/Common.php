<?php

namespace Dolphin\Ting\Constant;

/**
 * 通用
 */

class Common
{
    // 分页数量
    const PAGE_COUNT = 40;
    // 图片存储根目录
    const PHOTO_DIR = 'uploads';
    // 图片上传临时存储目录
    const PHOTO_DIR_TEMP = 'temp';
    // 阿里云 OSS 图片授权访问时长
    const OSS_VALID = 3600;
    // 阿里云 OSS 图片略缩图参数
    const OSS_PROCESS = 'image/resize,m_fill,h_200,w_200';
}
