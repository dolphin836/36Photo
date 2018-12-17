<?php

/**
 * 校验所有图片的 Hash 值
 * 
 * @author whb
 * @create 2018-12-17 10:05:00
 * @update 2018-12-17 10:05:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
// 设置临时最大内存
ini_set('memory_limit', '1024M');
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

// 图片 Hash
$image_hash = new ImageHash(new DifferenceHash());

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');
// 所有图片
$records = $db->select(Table::PICTURE, [
    'id',
    'hash',
    'path',
    'size'
], [
    'ORDER' => ['id' => 'ASC']
]);

var_dump('Photo Total Count:' . count($records));

foreach ($records as $record) {
    var_dump($record['id'] . '====>' . $record['hash'] . '=====>' . memory_get_usage());

    $photo  = ROOTPATH . 'public/' . $record['path'];

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
    // 计算图片 Hash 值
    $hash = $image_hash->hash($photo);
    // 和数据库中的 Hash 值比较
    if ($hash === $record['hash']) {
        var_dump('Photo Hash Is Ok.');
        // 删除图片
        unlink($photo);
    } else {
        var_dump('Photo Hash Is Error.');
        // Hash 不同的图片本地不删除，记录从后台删除后重新上传
    }
}







