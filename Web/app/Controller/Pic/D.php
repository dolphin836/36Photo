<?php

namespace Dolphin\Ting\Controller\Pic;

use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class D extends Pic
{
    public function __invoke(Request $request, Response $response, $args)
    { 
        $hash = $args['hash'];

        $record = $this->pic_model->record(["hash" => $hash]);
        // 生产环境
        if (is_null($record) || (getenv('DEBUG') === 'FALSE' && $record['is_oss'] === Common::IS_NOT_OSS)) {
            throw new NotFoundException($request, $response);
        }

        $photo  = '../../Manage/public/' . $record['path'];
        // 本地不存在则先从 OSS 下载
        if (! file_exists($photo)) {

            $dir = dirname($photo);

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            try {
                $this->oss_client->getObject(getenv('OSS_BUCKET_NAME'), $record['path'], [
                    OssClient::OSS_FILE_DOWNLOAD => $photo
                ]);
            } catch (OssException $e) {
                return $response->write($e->getMessage());
            }
        }

        $file   = fopen($photo, 'rb');
        $stream = new Stream($file);

        return $response->withBody($stream)
                        ->withHeader('Content-Disposition', 'attachment; filename=' . basename($photo) . ';')
                        ->withHeader('Content-Type', mime_content_type($photo))
                        ->withHeader('Content-Length', filesize($photo));
    }
}