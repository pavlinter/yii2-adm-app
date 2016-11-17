<?php

namespace app\helpers;

use Yii;

/**
 *
 */
class ModelHelper
{
    /**
     * @param $model
     * @param bool $exception
     * @return bool
     * @throws \yii\web\NotFoundHttpException
     */
    public static function checkModel($model, $exception = true)
    {
        if ($exception) {
            if ($model === null) {
                throw new \yii\web\NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
        } else {
            if ($model === null) {
                return false;
            }
        }
        return true;
    }
}
