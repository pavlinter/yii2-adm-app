<?php

return [
    'class' => 'app\components\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2-adm-app',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
        /* @var $sender \app\components\Connection */
        $sender = $event->sender;
        //$sender->pdo->exec('SET time_zone = "' . Yii::$app->timeZone . '"');
    },
];
