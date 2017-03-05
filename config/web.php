<?php
//version 0.0.2
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
require(__DIR__ . '/aliases.php');

Yii::$container->set('yii\validators\NumberValidator', [
    'class' => 'app\base\validators\NumberValidator',
]);

$config = [
    'name' => 'My Application',
    'id' => 'adm-app',
    'timeZone' => 'Europe/Riga',
    'basePath' => dirname(__DIR__),
    'layout' => '/main',
    'bootstrap' => [
        'log',
        'debug',
        'urlManager',
        'i18n',
        'gii',
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
        'gii' => [
            'class' => 'app\modules\admgii\Module',
            'allowedIPs' => ['127.0.0.1', '::1'],
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
                    'loginFormClass' => 'app\core\adm\models\LoginForm',
                    'authItemClass' => 'app\core\adm\models\AuthItem',
                    'authItemSearchClass' => 'app\core\adm\models\AuthItemSearch',
                    'authRuleClass' => 'app\core\adm\models\AuthRule',
                    'authRuleSearchClass' => 'app\core\adm\models\AuthRuleSearch',
                    'authItemChildClass' => 'app\core\adm\models\AuthItemChild',
                    'authItemChildSearchClass' => 'app\core\adm\models\AuthItemChildSearch',
                    'authAssignmentClass' => 'app\core\adm\models\AuthAssignment',
                    'authAssignmentSearchClass' => 'app\core\adm\models\AuthAssignmentSearch',
                    'languageClass' => 'app\core\adm\models\Language',
                    'languageSearchClass' => 'app\core\adm\models\LanguageSearch',
                    'sourceMessageClass' => 'app\core\adm\models\SourceMessage',
                    'sourceMessageSearchClass' => 'app\core\adm\models\SourceMessageSearch',
                    'messageClass' => 'app\core\adm\models\Message',
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
            'class' => 'pavlinter\admpages2\Module',
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
        'ar' => [
            'class' => 'app\modules\activeResponse\components\ActiveResponse',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'app\components\DbManager',
            //'cache' => 'cache', //this enables RBAC caching
        ],
        'mobileDetect' => [
            'class' => 'app\components\MobileDetect'
        ],
        'shortcode' => [
            'class' => 'app\components\Shortcode',
            'callbacks' => require(__DIR__ . '/shortcodes.php'),
        ],
        'urlManager' => [
            'class'=>'app\components\UrlManager', //https://github.com/pavlinter/yii2-url-manager
            'on beforeController' => function ($event) {
                require(__DIR__ . '/before-controllers.php');
            },
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
            'dialog' => 'mp',
            'router' => '/adm/source-message/dot-translation',
            'categoryUrl' => '@web/{lang}/adm/source-message/index?SourceMessageSearch[category]={category}',
            'translations' => [
                'app*' => [
                    'class' => 'app\components\DbMessageSource',
                    'forceTranslation' => true,
                    'dotMode' => true,
                ],
                'model*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'dotMode' => false,
                ],
                'adm*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'dotMode' => true,
                ],
                'modelAdm*' => [
                    'class' => 'pavlinter\translation\DbMessageSource',
                    'forceTranslation' => true,
                    'dotMode' => false,
                ],
            ],
        ],
        'assetManager' => [
            'class' => 'app\components\AssetManager',
            'appendTimestamp' => true,
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'class' => 'app\assets_b\BootstrapAsset',
                ],
                'yii\bootstrap\BootstrapThemeAsset' => [
                    'class' => 'app\assets_b\BootstrapThemeAsset',
                ],
                'kartik\icons\FontAwesomeAsset' => [
                    'depends' => [], //https://github.com/kartik-v/yii2-icons/issues/24
                ],
            ],
        ],
        'view' => [
            'class' => 'app\components\View',
            'title' => 'My title',
            'theme' => [
                'pathMap' => [
                    '@vendor/pavlinter/yii2-adm-pages2/admpages2/views' => '@app/views/admpages',
                    '@vendor/pavlinter/yii2-adm/adm/views' => '@app/core/adm/views',
                    '@vendor/mihaildev/yii2-elfinder/views' => '@app/core/elfinder/views',
                ],
            ],
        ],
        'request' => [
            'class' => 'app\components\Request',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FONQ3Oh7wxcvVI1ioWvnZ6gJzwjyBV6xU',
        ],
        'response' => [
            'class' => 'app\components\Response',
            //'on beforeSend' => function ($event) {},
        ],
        'formatter' => [
            'class' => 'app\components\Formatter',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss', // change Yii::$app->params['formatter.mysql.datetimeFormat']
            'dateFormat' => 'dd.MM.yyyy', // change Yii::$app->params['formatter.mysql.dateFormat']
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
            'numberFormatterOptions' => [
                7 => 0, //NumberFormatter::MIN_FRACTION_DIGITS
                6 => 2, //NumberFormatter::MAX_FRACTION_DIGITS
            ],
        ],
        'display' => [
            'class' => 'app\components\Display',
        ],
        'cache' => [
            'class' => 'app\components\FileCache',
        ],
        'errorHandler' => [
            'class' => 'app\components\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'app\components\Mailer',
            'viewPath' => '@app/mail',
        ],
        'security' => [
            'class' => 'app\components\Security'
        ],
        'session' => [
            'class' => 'app\components\Session'
        ],
        'log' => [
            'class' => 'app\components\Dispatcher',
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
                        'yii\web\HttpException:403', //Forbidden
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
