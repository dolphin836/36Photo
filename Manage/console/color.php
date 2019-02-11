<?php

/**
 * 设置图片的主要颜色
 * 
 * @author whb
 * @create 2019-02-01 17:30:00
 * @update 2019-02-01 17:30:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
// 设置临时最大内存
ini_set('memory_limit', '512M');
// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';
// 载入配置文件
$env = new Dotenv\Dotenv(ROOTPATH);
$env->load();
// 初始化数据库
$db = new Medoo([
    'database_type' => 'mysql',
    'database_name' => getenv('DB_NAME'),
           'server' => getenv('DB_HOST'),
         'username' => getenv('DB_USERNAME'),
         'password' => getenv('DB_PASSWORD'),
          'charset' => 'utf8'
]);
// 初始化 Oss Client
try {
    $oss_client = new OssClient(
        getenv('OSS_ACCESS_KEY_ID'),
        getenv('OSS_ACCESS_SECRET'),
        getenv('OSS_END_POINT')
    );
} catch (OssException $e) {
    printf(__FUNCTION__ . "阿里云 OSS 初始化失败。\n");
    printf($e->getMessage() . "\n");
    exit();
}

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');

// 所有图片
$records = $db->select(Table::PICTURE, [
    'id',
    'hash',
    'path'
], [
    'ORDER' => ['id' => 'ASC']
]);

var_dump('Photo Total Count:' . count($records));

foreach ($records as $record) {
    // 已经存在
    if($db->has(Table::PICTURE_COLOR, [
        'picture_hash' => $record['hash']
    ])) {
        continue;
    }
    // 本地存储路径
    $photo = ROOTPATH . 'public/' . $record['path'];
    // 本地不存在则先从 Oss 下载
    if (! file_exists($photo)) {
        $dir = dirname($photo);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $oss_client->getObject(getenv('OSS_BUCKET_NAME'), $record['path'], [
                OssClient::OSS_FILE_DOWNLOAD => $photo
            ]);

            var_dump('Download Photo Form OSS Success.');
        } catch (OssException $e) {
            var_dump('Download Photo Form OSS Failed.');
            continue;
        }
    }
    // 提取颜色
    $palette = Palette::fromFilename($photo);

    $extractor = new ColorExtractor($palette);

    $colors = $extractor->extract(5);

    foreach ($colors as $color) {
        $hex = Color::fromIntToHex($color);
        $hex = substr($hex, 1);

        $db->insert(Table::PICTURE_COLOR, [
            'picture_hash' => $record['hash'],
            'color'        => $hex
        ]);
    }
    // 删除本地文件
    unlink($photo);

    var_dump(date("Y-m-d H:i:s") . '====>' . $record['id'] . '====>' . $record['hash'] . '=====>' . round(memory_get_usage() / 1024 / 1024, 2) . ' mb');
}





