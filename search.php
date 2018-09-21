<?php

define('ROOTPATH', __DIR__);

// 载入自动加载文件
require ROOTPATH . '/vendor/autoload.php';

$server = 'https://api.unsplash.com';

$methon = '/photos/random';

$guzzle = new GuzzleHttp\Client();

while (1) {
    $result = $guzzle->request('GET', $server . $methon, [
        'headers' => [
            'Accept-Version' => 'v1',
            'Authorization'  => 'Client-ID 2c66d87a0031f85494c76ca1d0c1adb53a77a10b3a59eda45abe7252d6780444'
        ],
        'verify' => false
    ]);

    if ($result->getStatusCode() === 200) {
        $data = json_decode($result->getBody()->getContents());

        $result = $guzzle->request('GET', $data->urls->raw, [
            'sink' => upload($data->id),
            'verify' => false
        ]);

        if ($result->getStatusCode() === 200) {
            var_dump('Download Image Success:' . $data->id);
        }
    }
}

function upload($hash)
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

  $file_name = $hash . '.jpg';

  $file_path = './public/' . $dir . '/' . $file_name;

  return $file_path;
}