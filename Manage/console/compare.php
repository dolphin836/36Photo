<?php

/**
 * 更新图片相似关系
 * 
 * @author whb
 * @create 2018-12-10 16:30:00
 * @update 2018-12-10 16:30:00
 */

use Medoo\Medoo;
use Dolphin\Ting\Constant\Table;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

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
// 图片 Hash
$hash_diff = new ImageHash(new DifferenceHash());

const MAX_SPACE = 5;

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');

// 查询出全部的图片记录 Hash
$hashs = $db->select(Table::PICTURE, 'hash', [
    "ORDER" => ['id' => 'DESC']
]);

foreach ($hashs as $i => $picture_hash) {
    var_dump($picture_hash);

    foreach (array_slice($hashs, $i + 1) as $hash) {
        $space = $hash_diff->distance($picture_hash, $hash);

        if ($space <= MAX_SPACE) {
            // 先查询
            $record = $db->get(Table::PICTURE_COMPARE, [
                'hash'
            ], [
                'picture_hash' => $picture_hash
            ]);

            if (is_null($record)) { // 新增
                $db->insert(Table::PICTURE_COMPARE, [
                    'picture_hash' => $picture_hash,
                            'hash' => json_encode([
                                $hash
                            ])
                ]);
            } else { // 更新
                $has_hash = json_decode($record['hash']);

                if (count($has_hash) >= 10) { // 最多存储 10 个
                    continue;
                }

                array_push($has_hash, $hash);

                $db->update(Table::PICTURE_COMPARE, [
                    'picture_hash' => $picture_hash,
                            'hash' => json_encode($has_hash)
                ]);
            }
        }
    }
}





