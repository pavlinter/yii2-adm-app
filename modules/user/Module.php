<?php

namespace app\modules\user;

use Yii;

/**
 * Class Module
 * @package app\modules\user
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\user\controllers';

    public $layout = '@userRoot/views/layouts/center';


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            throw new \yii\web\NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        return true;
    }

    /**
     * @return self
     */
    public static function getInst()
    {
        return Yii::$app->getModule('user');
    }
}
