<?php

return [
    'class' => 'app\components\Connection',
    'dsn' => 'mysql:host=mysql;dbname=OwnDbName',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
        /* @var $sender \app\components\Connection */
        $sender = $event->sender;
        $timeZone = Yii::$app->timeZone;
        //$timeZone = date('P'); //or +02:00
        $sender->pdo->exec('SET time_zone="' . $timeZone . '"');
    },
];
