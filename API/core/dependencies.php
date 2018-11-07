<?php

$container = $app->getContainer();

// db
$container['db'] = function($c) {
    return new Medoo\Medoo([
        'database_type' => 'mysql',
        'database_name' => getenv('DB_NAME'),
               'server' => getenv('DB_HOST'),
             'username' => getenv('DB_USERNAME'),
             'password' => getenv('DB_PASSWORD'),
              'charset' => 'utf8',
    ]);
};