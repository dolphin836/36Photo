<?php

/**
 * 代码自动部署脚本
 */
// 报告所有错误
error_reporting(-1);

$commond = 'git pull';

$result  = shell_exec($commond);

var_dump($result);
