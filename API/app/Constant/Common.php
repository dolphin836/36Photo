<?php

namespace Dolphin\Ting\Constant;

/**
 * 通用
 */

class Common
{
    // 分页数量
    const PAGE_COUNT = 2;
    // 图片存储根目录
    const PHOTO_DIR = 'uploads';
    // 图片上传临时存储目录
    const PHOTO_DIR_TEMP = 'temp';
    // 阿里云 OSS 图片授权访问时长
    const OSS_VALID = 3600;
    // 阿里云 OSS 图片略缩图参数
    const OSS_PROCESS = 'image/resize,m_fill,h_200,w_200';
    // HTTP 请求返回状态码
    const ERROR_CODE_SUCCESS = 0; // 成功
    const ERROR_CODE_REQUEST = 1; // 请求参数错误
    const ERROR_CODE_DATA    = 2; // 数据请求错误
    const ERROR_CODE_AUTH    = 3; // 权限错误
    // HTTP 请求错误信息
    const ERROR_NOTE = [
        '请求成功.',
        '请求参数错误.',
        '请求数据错误.',
        '请求权限错误.'
    ];
}
