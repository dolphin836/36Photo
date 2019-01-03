<?php

/**
 * 更新图片的分类
 * 
 * @author whb
 * @create 2018-12-05 15:40:00
 * @update 2018-12-05 15:40:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;

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

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');
// 通过参数控制处理哪些记录：0 - 全部记录、1 - 未设置分类或分类为默认分类的记录
$mode = isset($argv[1]) ? (int) ($argv[1]) : 1;
// 默认处理全部记录
$fifter = [];

if ($mode) {
    $fifter['category_code'] = ['default', ''];
}
// 第二个参数为开始日期
if (isset($argv[2])) {
    $fifter['gmt_create[>]'] = $argv[2] . ' 00:00:00';
}

$records = $db->select(Table::PICTURE, 'hash', $fifter);

var_dump(date("Y-m-d H:i:s") . ':**********Total Record ' . count($records) . '**********');

foreach ($records as $hash) {
    // 查询标签
    $category_code = $db->get(Table::MARK, [
        "[>]" . Table::PICTURE_MARK => ["id" => "mark_id"],
    ], "category_code", [
        Table::PICTURE_MARK . ".picture_hash" => $hash
    ]);

    $query = $db->update(Table::PICTURE, [
        'category_code' => $category_code
    ], [
        'hash' => $hash
    ]);

    var_dump(date("Y-m-d H:i:s") . ':Photo ' . $hash . ' Set Category ' . $category_code); 
}

var_dump(date("Y-m-d H:i:s") . ':**********Set Photo Category Complete**********');
// 重置分类的图片数量
// 查询出所有分类
$category = $db->select(Table::CATEGORY, 'code');

foreach ($category as $category_code) {
    $photo_count = $db->count(Table::PICTURE, [
        'category_code' => $category_code
    ]);

    $db->update(Table::CATEGORY, [
        'count' => $photo_count
    ], [
        'code' => $category_code
    ]);  
}

var_dump(date("Y-m-d H:i:s") . ':**********Reset Category Photo Count Complete**********');





