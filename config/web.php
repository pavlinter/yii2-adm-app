<?php

if (YII_ENV_DEV) {
    $params = \yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
    );
    $db = \yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/db.php'),
        require(__DIR__ . '/db-local.php')
    );
} else {
    $params = require(__DIR__ . '/params.php');
    $db = require(__DIR__ . '/db.php');
}

$config = [
    'name' => 'My Application',
    'id' => 'app-core',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'debug',
        'urlManager',
        'i18n',
    ],
    'on beforeRequest' => function ($event) {
        $params = \pavlinter\admparams\models\Params::bootstrap();
        Yii::$app->params = \yii\helpers\ArrayHelper::merge(Yii::$app->params, $params);
        \pavlinter\admeconfig\models\EmailConfig::changeMailConfig();
    },
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['127.0.0.1', '::1']
        ],
        'adm' => [
            'class' => 'pavlinter\adm\Adm',
            'modules' => [
                'appadm',
                'admpages',
                'admunderconst',
                'admgoogletools',
                'admlivechat',
                'admparams',
                'admeconfig',
                'admhidemenu',
            ],
            'components' => [
                'manager' => [
                    'userClass' => 'app\models\User',
                    'userSearchClass' => 'app\core\adm\models\UserSearch',
                    /*
                    'loginFormClass' => 'pavlinter\adm\models\LoginForm',
                    'authItemClass' => 'pavlinter\adm\models\AuthItem',
                    'authItemSearchClass' => 'pavlinter\adm\models\AuthItemSearch',
                    'authRuleClass' => 'pavlinter\adm\models\AuthRule',
                    'authRuleSearchClass' => 'pavlinter\adm\models\AuthRuleSearch',
                    'authItemChildClass' => 'pavlinter\adm\models\AuthItemChild',
                    'authItemChildSearchClass' => 'pavlinter\adm\models\AuthItemChildSearch',
                    'authAssignmentClass' => 'pavlinter\adm\models\AuthAssignment',
                    'authAssignmentSearchClass' => 'pavlinter\adm\models\AuthAssignmentSearch',
                    'languageClass' => 'pavlinter\adm\models\Language',
                    'languageSearchClass' => 'pavlinter\adm\models\LanguageSearch',
                    'sourceMessageClass' => 'pavlinter\adm\models\SourceMessage',
                    'sourceMessageSearchClass' => 'pavlinter\adm\models\SourceMessageSearch',
                    'messageClass' => 'pavlinter\adm\models\Message',
                    */
                ],
            ],
            'controllerMap' => [
                'user' => [
                    'class' => 'app\core\adm\controllers\UserController',
                ],
                'elfinder' => require(__DIR__ . '/elfinder.php'),
            ],
        ],
        'profilelogin' => [
            'class' => 'app\modules\profilelogin\Module',
        ],
        'appadm' => [
            'class' => 'app\modules\appadm\Module',
        ],
        'admgoogletools' => [
            'class' => 'app\modules\admgoogletools\Module',
        ],
        'admlivechat' => [
            'class' => 'app\modules\admlivechat\Module',
        ],
        'admunderconst' => [
            'class' => 'app\modules\admunderconst\Module',
        ],
        'admhidemenu' => [
            'class' => 'app\modules\admhidemenu\Module',
        ],
        'cloud' => [
            'class' => 'app\modules\cloud\Cloud',
        ],
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
        ],
        'admpages' => [
            'class' => 'pavlinter\admpages\Module',
            'pageLayouts' => function ($m) {
                return [
                    'main' => $m::t('layouts', 'Main Page', ['dot' => false]),
                    'contact' => $m::t('layouts', 'Contact', ['dot' => false]),
                ];
            },
            'pageRedirect' => [
                'contact' => ['pages/contact'],
            ],
            'pageTypes' => function ($m) {
                return [];
            },
            'pageLayout' => '/main',
            'closeDeletePage' => [1,2,3], //id [2,130]
            'files' => [
                'page' => [
                    'dirs' => [
                        '@webroot/files/data/pages/{id}/gallery'// {id} - id page
                    ],
                    'startPath' => 'data::pages::{id}', // where :: replace to /
                ],
                'main' => [
                    'dirs' => [
                        '@webroot/files/data/pages/{id}/gallery'
                    ],
                    'startPath' => 'data::pages::{id}',
                ],
            ],
            'components' => [
                'manager' => [
                    'pageSearchClass' => 'app\core\admpages\models\PageSearch',
                ],
            ],
        ],
        'admparams' => [
            'class' => 'pavlinter\admparams\Module',
        ],
        'admeconfig' => [
            'class' => 'pavlinter\admeconfig\Module',
        ],
        'display2'=> [
            'class'=>'pavlinter\display2\Module',
            'categories' => [
                'pages' => [
                    'imagesWebDir' => '@web/files/data/pages',
                    'imagesDir' => '@webroot/files/data/pages',
                    'defaultWebDir' => '@web/files/default',
                    'defaultDir' => '@webroot/files/default',
                    'mode' => \pavlinter\display2\objects\Image::MODE_OUTBOUND,
                ],
            ],
        ],
    ],
    'components' => [
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'cache' => 'cache', //this enables RBAC caching
        ],
        'urlManager' => [
            'class'=>'app\components\UrlManager', //https://github.com/pavlinter/yii2-url-manager
            'enableLang' => true,
            'langBegin' => ['en','ru', 'lv'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'ruleConfig' => [
                'class' => '\pavlinter\urlmanager\UrlRule',
            ],
            'rules' => [
                '' => 'admpages/default/main',
                'page/<alias:([A-Za-z0-9_-])+>' => 'admpages/default/index',
            ],
        ],
        'i18n' => [
            'class'=>'app\components\I18N', //https://github.com/pavlinter/yii2-dot-translation
            'access' => function () {
                return !Yii::$app->user->isGuest && Yii::$app->user->can('Adm-Transl');
            },
            'dialog' => 'bs',
            'router' => '/adm/source-message/dot-translation',
            'categoryUrl' => '@web/{lang}/adm/source-message/index?SourceMessageSearch[category]={category}',
            'translations' => [
                'app*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'autoInsert' => true,
                    'dotMode' => true,
                ],
                'model*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'autoInsert' => true,
                    'dotMode' => false,
                ],
                'adm*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'autoInsert' => true,
                    'dotMode' => true,
                ],
                'modelAdm*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'autoInsert' => true,
                    'dotMode' => false,
                ],
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'basePath' => '@webroot/assets_b/common',
                    'baseUrl' => '@web/assets_b/common',
                    'sourcePath' => null,   // do not publish the bundle
                ],
            ],
        ],
        'view' => [
            'title' => 'My title',
            'theme' => [
                'pathMap' => [
                    '@vendor/pavlinter/yii2-adm-pages/admpages/views' => '@app/views/admpages',
                    '@vendor/pavlinter/yii2-adm/adm/views' => '@app/core/adm/views',
                    '@vendor/mihaildev/yii2-elfinder/views' => '@app/core/elfinder/views',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FONQ3Oh7wxcvVI1ioWvnZ6gJzwjyBV6xU',
        ],
        /*'response' => [
            'on beforeSend' => function ($event) {


            },
        ],*/
        'formatter' => [
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss', // change Yii::$app->params['formatter.mysql.datetimeFormat']
            'dateFormat' => 'dd.MM.yyyy', // change Yii::$app->params['formatter.mysql.dateFormat']
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
        'display' => [
            'class' => 'app\components\Display',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                /*'email' => [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\i18n\PhpMessageSource*',
                        'yii\i18n\I18N*',
                    ],
                    'message' => [
                        'from' => ['noreply@gmail.com'],
                        'to' => [
                            //'test@gmail.com'
                        ],
                        'subject' => 'Error: ' . $_SERVER['SERVER_NAME'],
                    ],
                ],*/
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

return $config;
