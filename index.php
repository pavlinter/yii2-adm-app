<?php
if($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');

    require(__DIR__ . '/vendor/autoload.php');
    require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

    $config = \yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/config/web.php'),
        require(__DIR__ . '/config/web-local.php')
    );
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'prod');

    require(__DIR__ . '/vendor/autoload.php');
    require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

    $config = require(__DIR__ . '/config/web.php');
}

(new \app\modules\Application($config))->run();



