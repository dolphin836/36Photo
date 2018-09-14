<?php

/**
 * 调用阿里云的图像识别服务给图片添加标签
 */
class Mark
{
    // 启用阀值，大于此值的标签才会被使用
    private $level = 20;
    //
    private $server = 'https://dtplus-cn-shanghai.data.aliyuncs.com/image/tag';
    // 阿里云
    private $access_key_id = '';
    private $access_secret = '';

    function __construct($access_key_id, $access_secret)
    {
        $this->access_key_id = $access_key_id;
        $this->access_secret = $access_secret;
    }

    public function run($picture_url)
    {
        $content = [
                 'type' => 0,
            'image_url' => $picture_url
        ];

        $response = file_get_contents($this->server, false, $this->sign($this->server, $content));

        $json = json_decode($response, true);

        $marks = [];
        
        if ($json['errno'] == 0) { // 成功
            foreach ($json['tags'] as $tag) {
                if ((int) $tag['confidence'] >= $this->level) {
                    $marks[] = $tag['value'];
                }
            }
        }

        return $marks;
    }

    private function sign($server, $content)
    {
        $options = [
            'http' => [
                 'header' => [
                           'accept' => "application/json",
                     'content-type' => "application/json",
                             'date' => gmdate("D, d M Y H:i:s \G\M\T"),
                    'authorization' => ''
                ],
                 'method' => "POST",
                'content' => json_encode($content)
            ]
        ];

        $http   = $options['http'];
        $header = $http['header'];
        $path   = parse_url($server)["path"];
        $body   = $http['content'];

        if(empty($body)) {
            $bodymd5 = $body;
        } else {
            $bodymd5 = base64_encode(md5($body,true));
        }

        $stringToSign = $http['method'] . "\n" . $header['accept'] . "\n" . $bodymd5 . "\n" . $header['content-type'] . "\n" . $header['date'] . "\n". $path;

        $signature = base64_encode(
            hash_hmac(
                "sha1",
                $stringToSign,
                $this->access_secret,
                true
            )
        );

        $authHeader = "Dataplus " . $this->access_key_id . ":" . $signature;

        $options['http']['header']['authorization'] = $authHeader;

        $options['http']['header'] = implode(
            array_map(
                function ($key, $value) {
                    return $key . ":" . $value . "\r\n";
                },
                array_keys($options['http']['header']),
                $options['http']['header']
            )
        );

        return stream_context_create($options);
    }
}

