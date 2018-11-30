<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Model\Mark_model;
use Dolphin\Ting\Model\Collection_model;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class GetDelete extends Pic
{
    private $mark_model;

    private $collection_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->mark_model = new Mark_model($app);

        $this->collection_model = new Collection_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {     
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $hash = $querys['hash'];

        $pic  = $this->pic_model->record(["hash" => $hash]);
        // 开始事务
        $this->app->db->pdo->beginTransaction();

        try {
            // 删除图片的主记录
            $this->pic_model->delete(["hash" => $hash]);
            // 删除图片的标签记录
            $this->mark_model->delete_pic($hash);
            // 删除图片的颜色记录
            $this->pic_model->delete_color($hash);
            // 删除专题中的图片记录
            $this->collection_model->delete_pic($hash);
            // 提交事务
            $this->app->db->pdo->commit();
            // 删除本地文件
            @unlink($pic['path']);
            // 删除云端文件
            if ($pic['is_oss'] === Common::IS_OSS) {
                try {
                    $this->oss_client->deleteObject(getenv('OSS_BUCKET_NAME'), $pic['path']);
                } catch (OssException $e) {
                    var_dump($e->getMessage());
                }
            }
            
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除图片成功'
            ]);
        } catch (Exception $e) {
            // 回滚事务
            $this->app->db->pdo->rollBack();

            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除图片失败'
            ]);
        }

        return $response->withRedirect('/pic/records', 302);
    }
}