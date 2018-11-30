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
    // 阿里云 OSS 图片授权访问时长
    const OSS_VALID = 3600;
    // 阿里云 OSS 图片略缩图参数
    const OSS_PROCESS = 'image';
    const OSS_PROCESS_RESIZE_320 = '/resize,m_lfit,w_360';
    const OSS_PROCESS_RESIZE_640 = '/resize,m_lfit,w_1080';
    // 阿里云 OSS 图片格式转换参数
    const OSS_PROCESS_FORMAT = '/format,webp';
    // 已上传阿里云 OSS
    const IS_OSS = '1';
    // 未上传阿里云 OSS
    const IS_NOT_OSS = '0';
    // 标签聚合页展示的标签数量
    const MARK_MAX_COUNT = 60;
    // 颜色聚合页展示的颜色数量
    const COLOR_MAX_COUNT = 40;
}
