<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Constant\Nav;

class User extends \Dolphin\Ting\Controller\Base
{
    protected $conf = [
        
    ];

    protected $group = [
        '普通用户',
        '管理员',
        '超级管理员'
    ];
    
    protected $client = [
        '系统创建',
        'PC web',
        '移动 web',
        'iOS',
        'Android',
        '微信小程序'
    ];
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, Table::USER);

        $this->nav = Nav::USER;
    }
}