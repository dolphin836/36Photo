<?php

/**
 * 校正颜色主表数据
 * 
 * @author whb
 * @create 2019-02-02 14:50:00
 * @update 2019-02-02 14:50:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;

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

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');

// 从图片 - 颜色的对应关系表中查询出所有记录
$records = $db->select(Table::PICTURE_COLOR, [
    'color'
], [
    'ORDER' => ['id' => 'ASC']
]);

foreach ($records as $record) {
    $color = $record['color'];
    var_dump($color);
    // 主表中不存在，则插入记录
    if(! $db->has(Table::COLOR, [
        'color' => $color
    ])) {
        $db->insert(Table::COLOR, [
            'color' => $color
        ]);
    }
    // 重新计算图片数量
    $total = $db->count(Table::PICTURE_COLOR, [
        'color' => $color
    ]);

    if ($total) {
        $db->update(Table::COLOR, [
            'count' => $total
        ],[
            'color' => $color
        ]);
    }
}





