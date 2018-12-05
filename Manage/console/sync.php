<?php

/**
 * 图片同步脚本
 * 通过后台批量上传的图片会先只上传至服务器
 * 然后通过此脚本同步上传至阿里云 OSS，并设置标签、分类、颜色
 * @author whb
 * @create 2018-10-31 17:40:00
 * @update 2018-10-31 17:40:00
 */

use Medoo\Medoo;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Dolphin\Ting\Constant\Table;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';
// 载入设置标签类文件
require BASEPATH . '/mark.php';
// 载入配置文件
$env = new Dotenv\Dotenv(ROOTPATH);
$env->load();
// 运行环境
$is_debug = getenv("DEBUG") === "TRUE" ? true : false;
// 初始化数据库
$db = new Medoo([
    'database_type' => 'mysql',
    'database_name' => getenv('DB_NAME'),
           'server' => getenv('DB_HOST'),
         'username' => getenv('DB_USERNAME'),
         'password' => getenv('DB_PASSWORD'),
          'charset' => 'utf8'
]);
// oss client
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
// 标签
$mark = new Mark(getenv('OSS_ACCESS_KEY_ID'), getenv('OSS_ACCESS_SECRET'));

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');

// 查询所有未上传阿里云 OSS 的图片记录
$records = $db->select(Table::PICTURE, '*', [
    'is_oss' => 0
]);

foreach ($records as $record) {
    // 同步至阿里云 OSS
    if (sync($oss_client, $record['path'])) {
        // 更新记录
        $db->update(Table::PICTURE, [
            'is_oss' => 1
        ], [
            'hash' => $record['hash']
        ]);
        // 智能设置标签
        $valid = 60;

        try {
            $pic = $oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid);
        } catch (OssException $e) {
            continue;
        }

        $marks = $mark->run($pic);

        foreach ($marks as $mark_name) {
            if (! $db->has(Table::MARK, ['name' => $mark_name])) {
                $query = $db->insert(Table::MARK, [
                    'name' => $mark_name
                ]);

                $mark_id = $db->id();
            } else {
                $query = $db->update(Table::MARK, [
                    'count[+]' => 1
                ], [
                    'name' => $mark_name
                ]);

                $mark_info = $db->get(Table::MARK, [
                    'id'
                ], [
                    'name' => $mark_name
                ]);

                $mark_id = $mark_info['id'];
            }

            if ($query->rowCount()) {
                $db->insert(Table::PICTURE_MARK, [
                    'picture_hash' => $record['hash'],
                         'mark_id' => $mark_id
                ]);
            }

            var_dump(date("Y-m-d H:i:s") . ':Add Mark Success:' . $mark_name);
        }
    }
    
}

// 同步至阿里云 OSS
function sync($oss_client, $pic)
{
    try {
        $oss_client->uploadFile(getenv('OSS_BUCKET_NAME'), $pic, ROOTPATH . '/public/' . $pic);

        var_dump(date("Y-m-d H:i:s") . ':OSS Upload Over.');

        return true;
    } catch (OssException $e) {
        var_dump(date("Y-m-d H:i:s") . ':OSS Upload Faild.');

        return false;
    }
}





