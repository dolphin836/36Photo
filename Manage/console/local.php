<?php

/**
 * 下载图片到本地
 * 
 * @author whb
 * @create 2018-12-17 10:05:00
 * @update 2018-12-17 10:05:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
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
    'hash',
    'path'
], [
    'ORDER' => ['id' => 'DESC']
]);

var_dump('Photo Total Count:' . count($records));

foreach ($records as $record) {
    var_dump($record['hash']);

    $photo  = ROOTPATH . 'public/' . $record['path'];

    // 本地不存在
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
}







