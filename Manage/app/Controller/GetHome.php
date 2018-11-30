<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Model\Mark_model;
use Dolphin\Ting\Model\User_model;

class GetHome extends Base
{
    protected $pic_model;

    protected $mark_model;

    protected $user_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::HOME;

        $this->pic_model  = new Pic_model($app);
        $this->mark_model = new Mark_model($app);
        $this->user_model = new User_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {   
        $fifter = [
            'gmt_create[>=]' => date("Y-m-d ") . '00:00:00',
            'gmt_create[<=]' => date("Y-m-d ") . '23:59:59'
        ];

        $data = [
            'photo_total' => $this->pic_model->total(),
            'photo_today' => $this->pic_model->total($fifter),
            'mark_total'  => $this->mark_model->total(),
            'mark_today'  => $this->mark_model->total($fifter),
            'user_total'  => $this->user_model->total(),
            'user_today'  => $this->user_model->total($fifter)
        ];
        
        $this->respond('Home', $data);
    }
}