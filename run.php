<?php

use Medoo\Medoo;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Spatie\ImageOptimizer\OptimizerChainFactory;

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
// 图片优化
$image_opt = OptimizerChainFactory::create();
// oss client
try {
  $oss_client = new \OSS\OSSClient(
    getenv('OSS_ACCESS_KEY_ID'),
    getenv('OSS_ACCESS_SECRET'),
    getenv('OSS_END_POINT')
  );
} catch (\OSS\Core\OSSException $e) {
  printf(__FUNCTION__ . "阿里云 OSS 初始化失败。\n");
  printf($e->getMessage() . "\n");
  exit();
}
// 标签
$mark = new Mark(getenv('OSS_ACCESS_KEY_ID'), getenv('OSS_ACCESS_SECRET'));

var_dump(date("Y-m-d H:i:s") . ':**********Start Run**********');

found('./public/picture', $image_hash, $db, $oss_client, $mark, $image_opt);

/**
 * 遍历文件夹，处理图片
 */
function found($dir, $image_hash, $db, $oss_client, $mark, $image_opt)
{
  $results = new \FilesystemIterator($dir);

  foreach($results as $result)
  {
      // 递归目录
      if ($result->isDir()) {
        found($result->getPathname(), $image_hash, $db, $oss_client, $mark, $image_opt);
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
      $upload = upload($path, $result->getExtension(), $image_opt);

      if (! $upload) { // 移动到上传目录失败
        continue;
      }

      var_dump(date("Y-m-d H:i:s") . ':Move To:' . $upload);

      $is_oss = 1;

      try {
          $oss_client->uploadFile(getenv('OSS_BUCKET_NAME'), $upload, './public/' .$upload);
      } catch (\OSS\Core\OSSException $e) {
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
          $valid = 60;

          try {
            $pic = $oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $upload, $valid);
          } catch (\OSS\Core\OSSException $e) {
            continue;
          }

          $marks = $mark->run($pic);

          foreach ($marks as $mark_name) {
            if (! $db->has('mark', ['name' => $mark_name])) {
              $query = $db->insert('mark', [
                'name' => $mark_name
              ]);

              $mark_id = $db->id();
            } else {
              $query = $db->update('mark', [
                'count[+]' => 1
              ], [
                'name' => $mark_name
              ]);

              $mark_info = $db->get('mark', [
                'id'
              ], [
                'name' => $mark_name
              ]);

              $mark_id = $mark_info['id'];
            }

            if ($query->rowCount()) {
              $db->insert('picture_mark', [
                'picture_hash' => $hash,
                     'mark_id' => $mark_id
              ]);
            }

            var_dump(date("Y-m-d H:i:s") . ':Add Mark Success:' . $mark_name);
          }
        }
      }
  }
}

/**
 * 移动到上传目录
 */
function upload($path, $extension, $image_opt)
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

  $image_opt->optimize($path, './public/' . $file_path);

  return $file_path;
}



