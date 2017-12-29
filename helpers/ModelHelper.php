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


    /**
     * @param $model \yii\base\Model
     * @param $attribute
     * @return bool
     */
    public static function inScenario($model, $attribute)
    {
        $attribute = Html::getAttributeName($attribute);

        $scenarios = $model->scenarios();
        $scenario = $model->getScenario();
        if (!isset($scenarios[$scenario])) {
            throw new \yii\base\InvalidParamException("Unknown scenario: $scenario");
        }

        $attributeNames = $model->activeAttributes();
        return in_array($attribute, $attributeNames);
    }
}
