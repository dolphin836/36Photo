<?php

/**
 * 代码自动部署脚本
 */

$commond = 'git pull';

$result  = shell_exec($commond);

var_dump($result);