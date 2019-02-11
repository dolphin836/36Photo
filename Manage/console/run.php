<?php

/**
 * 自动添加本地的图片（包括入库、上传阿里云 OSS、智能设置标签、获取主色）
 * @author whb
 * @create 2018-09-14 18:18:00
 * @update 2018-11-21 11:00:00
 */

use Medoo\Medoo;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// 设置时区
date_default_timezone_set('PRC');
// 设置临时最大内存
ini_set('memory_limit', '1024M');
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
    'server'        => getenv('DB_HOST'),
    'username'      => getenv('DB_USERNAME'),
    'password'      => getenv('DB_PASSWORD'),
    'charset'       => 'utf8'
]);
// 图片 Hash
$image_hash = new ImageHash(new DifferenceHash());
// 图片优化
$image_opt = OptimizerChainFactory::create();
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
// 获取超级管理员的 UUID
$users = $db->select(Table::USER, ['uuid'], [
    'group' => 2,
    'LIMIT' => [0, 10]
]);

$uuid = [];

foreach ($users as $user) {
    $uuid[] = $user['uuid'];
}
// 获取专题编号
$collection_code = isset($argv[1]) ? $argv[1] : '';

if ($collection_code !== '') { // 判断是否存在
    if (! $db->has(Table::COLLECTION, [
        'code' => $collection_code
    ])) {
        $collection_code = '';
    }
}

found(ROOTPATH . '/public/picture', $image_hash, $db, $oss_client, $mark, $image_opt, $is_debug, $uuid, $collection_code);

/**
 * 遍历文件夹，处理图片
 */
function found ($dir, $image_hash, $db, $oss_client, $mark, $image_opt, $is_debug, $uuid, $collection_code)
{
    $results = new \FilesystemIterator($dir);

    foreach ($results as $result) {
        // 递归目录
        if ($result->isDir()) {
            found($result->getPathname(), $image_hash, $db, $oss_client, $mark, $image_opt, $is_debug, $uuid, $collection_code);
            // 删除目录
            rmdir($result->getPathname());
        }

        // 过滤
        if (! $result->isFile()) continue;

        $path = $result->getPathname();

        var_dump(date("Y-m-d H:i:s") . ':Found Picture:' . $path);

        $hash = $image_hash->hash($path);

        var_dump(date("Y-m-d H:i:s") . ':Hash is:' . $hash);

        // 已经存在
        if ($db->has('picture', ['hash' => $hash])) {
            unlink($path);
            var_dump(date("Y-m-d H:i:s") . ':The Picture Is Exist.');
            continue;
        }

        list($width, $height, $type, $attr) = getimagesize($path);
        // 移动到上传目录
        $upload = upload($path, $result->getExtension(), $image_opt);

        if (! $upload) { // 移动到上传目录失败
            continue;
        }

        var_dump(date("Y-m-d H:i:s") . ':Move To:' . $upload);
        // 获取压缩后的文件大小
        $size = filesize(ROOTPATH . '/public/' . $upload);
        // 生产环境下上传 OSS
        $is_oss = Common::IS_NOT_OSS;

        if (! $is_debug) {
            try {
                $oss_client->uploadFile(getenv('OSS_BUCKET_NAME'), $upload, ROOTPATH . '/public/' . $upload);
                $is_oss = Common::IS_OSS;
            } catch (OssException $e) {
                var_dump(date("Y-m-d H:i:s") . ':OSS Upload Failed.');
            }

            var_dump(date("Y-m-d H:i:s") . ':OSS Upload Over.');
        }

        $data = [
            'hash'   => $hash,
            'uuid'   => $uuid[array_rand($uuid)],
            'width'  => $width,
            'height' => $height,
            'path'   => $upload,
            'size'   => $size,
            'is_oss' => $is_oss
        ];

        $query = $db->insert(Table::PICTURE, $data);

        if ($query->rowCount()) {
            var_dump(date("Y-m-d H:i:s") . ':Insert Picture Success:' . $hash);
            // 主要颜色
            $palette   = Palette::fromFilename(ROOTPATH . '/public/' . $upload);
            $extractor = new ColorExtractor($palette);
            $colors    = $extractor->extract(5);

            foreach ($colors as $color) {
                $hex = Color::fromIntToHex($color);
                $hex = substr($hex, 1);
                // 图片 - 颜色 关联表
                $db->insert(Table::PICTURE_COLOR, [
                    'picture_hash' => $hash,
                    'color'        => $hex
                ]);
                // 颜色主表
                if (! $db->has(Table::COLOR, ['color' => $hex])) {
                    // 新增
                    $db->insert(Table::COLOR, [
                        'color' => $hex
                    ]);
                } else {
                    // 数量加一
                    $db->update(Table::COLOR, [
                        'count[+]' => 1
                    ], [
                        'color' => $hex
                    ]);
                }
            }

            // 标签
            if ($is_oss) { // 只处理 OSS 上传成功的
                $valid = 60;

                try {
                    $pic = $oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $upload, $valid);
                } catch (OssException $e) {
                    continue;
                }

                $marks = $mark->run($pic);
                // 默认分类
                $category_code = 'default';

                foreach ($marks as $mark_name) {
                    if (! $db->has(Table::MARK, ['name' => $mark_name])) {
                        $query = $db->insert(Table::MARK, [
                            'name' => $mark_name
                        ]);
                    } else {
                        $query = $db->update(Table::MARK, [
                            'count[+]' => 1
                        ], [
                            'name' => $mark_name
                        ]);
                    }

                    if ($query->rowCount()) {
                        $mark_info = $db->get(Table::MARK, [
                            'id',
                            'category_code'
                        ], [
                            'name' => $mark_name
                        ]);

                        $mark_id = $mark_info['id'];

                        $db->insert(Table::PICTURE_MARK, [
                            'picture_hash' => $hash,
                            'mark_id'      => $mark_id
                        ]);

                        if ($category_code === 'default' && $mark_info['category_code'] !== 'default') {
                            $category_code = $mark_info['category_code'];
                        }
                    }

                    var_dump(date("Y-m-d H:i:s") . ':Add Mark Success:' . $mark_name);
                }
                // 设置分类
                if ($category_code !== 'default') {
                    $query = $db->update(Table::PICTURE, [
                        'category_code' => $category_code
                    ], [
                        'hash' => $hash
                    ]);

                    if ($query->rowCount()) { // 更新成功，分类的图片数量加一
                        $db->update(Table::CATEGORY, [
                            'count[+]' => 1
                        ], [
                            'code' => $category_code
                        ]);
                    }

                    var_dump(date("Y-m-d H:i:s") . ':Set Category:' . $category_code);
                }
            }
            // 专题
            if ($collection_code !== '') {
                $db->insert(Table::PICTURE_COLLECTION, [
                    'picture_hash'    => $hash,
                    'collection_code' => $collection_code
                ]);

                var_dump(date("Y-m-d H:i:s") . ':Set Collection:' . $collection_code);
            }

            var_dump('memory used ' . round(memory_get_usage() / 1024 / 1024, 2) . ' mb');
        }
    }
}

/**
 * 移动到上传目录
 */
function upload ($path, $extension, $image_opt)
{
    $upload = 'uploads';

    $time   = time();

    $y = date("Y", $time);
    $m = date("m", $time);
    $d = date("d", $time);

    $dir = $upload . '/' . $y . '/' . $m . '/' . $d;

    if (! is_dir(ROOTPATH . '/public/' . $dir)) {
        mkdir(ROOTPATH . '/public/' . $dir, 0755, true);
    }

    $file_name = md5_file($path) . '.' . $extension;

    $file_path = $dir . '/' . $file_name;

    $image_opt->optimize($path, ROOTPATH . '/public/' . $file_path);

    unlink($path);

    return $file_path;
}



