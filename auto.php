<?php

use Medoo\Medoo;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use OSS\OSSClient;
use OSS\Core\OSSException;

define('ROOTPATH', __DIR__);
// 设置时区
date_default_timezone_set('PRC');
// 设置临时最大内存
ini_set('memory_limit', '512M');
// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';
// 载入设置标签类文件
require ROOTPATH . '/mark.php';
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
$image_hash = new ImageHash(new DifferenceHash());
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

found('./public/picture', $image_hash, $db, $oss_client, $mark);

/**
 * 遍历文件夹，处理图片
 */
function found($dir, $image_hash, $db, $oss_client, $mark)
{
  $results = new \FilesystemIterator($dir);

  foreach($results as $result)
  {
      // 递归目录
      if ($result->isDir()) {
          found($result->getPathname(), $image_hash, $db, $oss_client, $mark);
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
        var_dump(date("Y-m-d H:i:s") . ':The Picture Is Exist.');
        continue;
      }

      $size = $result->getSize();

      list($width, $height, $type, $attr) = getimagesize($path);
      // 移动到上传目录
      $upload = upload($path, $result->getExtension());

      if (! $upload) { // 移动到上传目录失败
        continue;
      }

      var_dump(date("Y-m-d H:i:s") . ':Move To:' . $upload);

      $is_oss = 1;

      try {
          $oss_client->uploadFile(getenv('OSS_BUCKET_NAME'), $upload, './public/' .$upload);
      } catch (OssException $e) {
          $is_oss = 0;
          var_dump(date("Y-m-d H:i:s") . ':OSS Upload Faild.');
      }

      var_dump(date("Y-m-d H:i:s") . ':OSS Upload Over.');

      $data = [
          'hash' => $hash,
          'uuid' => 'a22c38198f4b4ad992c4a1b89123d6e3',
         'width' => $width,
        'height' => $height,
          'path' => $upload,
          'size' => $size,
        'is_oss' => $is_oss
      ];

      $query = $db->insert('picture', $data);

      if ($query->rowCount()) {
        var_dump(date("Y-m-d H:i:s") . ':Insert Picture Success:' . $hash);
        // 图片操作成功后继续添加标签
        if ($is_oss) { // 只处理 OSS 上传成功的
          $valid = 10;

          try {
            $pic = $oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $upload, $valid);
          } catch (OssException $e) {
            continue;
          }

          $marks = $mark->run($pic);

          foreach ($marks as $mark) {
            if (! $db->has('mark', ['name' => $mark])) {
              $query = $db->insert('mark', [
                'name' => $mark
              ]);

              $mark_id = $db->id();
            } else {
              $query = $db->update('mark', [
                'count[+]' => 1
              ], [
                'name' => $mark
              ]);

              $mark = $db->get('mark', [
                'id'
              ], [
                'name' => $mark
              ]);

              $mark_id = $mark['id'];
            }

            if ($query->rowCount()) {
              $db->insert('picture_mark', [
                'picture_hash' => $hash,
                     'mark_id' => $mark_id
              ]);
            }

            var_dump(date("Y-m-d H:i:s") . ':Add Mark Success:' . $mark);
          }
        }
      }
  }
}

/**
 * 移动到上传目录
 */
function upload($path, $extension)
{
  $upload = 'uploads';

  $time = time();

  $y = date("Y", $time);
  $m = date("m", $time);
  $d = date("d", $time);

  $dir = $upload . '/' . $y . '/' . $m . '/' . $d;

  if (! is_dir('./public/' . $dir)) {
    mkdir('./public/' . $dir, 0755, true);
  }

  $file_name = md5_file($path) . '.' . $extension;

  $file_path = $dir . '/' . $file_name;

  if (rename($path, './public/' . $file_path)) {
    return $file_path;
  }

  return false;
}



