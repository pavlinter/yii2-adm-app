<?php

return [
    'class' => 'app\components\Connection',
    'dsn' => 'mysql:host=mysql;dbname=OwnDbName',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
        /* @var $sender \yii\db\Connection */
        $sender = $event->sender;
        $sender->pdo->exec('SET time_zone = "' . Yii::$app->timeZone . '"');
    },
];
