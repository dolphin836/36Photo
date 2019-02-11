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
    // 列表页小图
    const PHOTO_RESIZE_SMA = '/resize,m_lfit,w_140';
    // 列表页大图
    const PHOTO_RESIZE_LAR = '/resize,m_lfit,w_560';
    // 推荐页小图
    const RECOMMEND_RESIZE_SMA = '/resize,m_fill,w_140';
    // 推荐页大图
    const RECOMMEND_RESIZE_LAR = '/resize,m_fill,w_280';
    // 阿里云 OSS 图片格式转换参数
    const OSS_PROCESS_FORMAT = '/format,webp';
    // 已上传阿里云 OSS
    const IS_OSS = '1';
    // 未上传阿里云 OSS
    const IS_NOT_OSS = '0';
    // 标签聚合页展示的标签数量
    const MARK_MAX_COUNT = 100;
    // 颜色聚合页展示的颜色数量
    const COLOR_MAX_COUNT = 100;
    // 每日推荐显示的最大图片数量
    const RECOMMEND_DAY_MAX = 10;
    // 最新推荐显示的最大图片数量
    const RECOMMEND_NEW_MAX = 10;
    // 列表页本地图片的处理模式
    const PHOTO_LOCAL_MODE = 'resize';
    // 列表页本地图片的小图宽度
    const PHOTO_LOCAL_SMA  = 560;
    // 列表页本地图片的大图宽度
    const PHOTO_LOCAL_LAR  = 560;
    // 推荐页本地图片的处理模式
    const RECOMMEND_LOCAL_MODE = 'fit';
    // 推荐页本地图片的小图宽度
    const RECOMMEND_LOCAL_SMA  = 280;
    // 推荐页本地图片的大图宽度
    const RECOMMEND_LOCAL_LAR  = 280;
}
