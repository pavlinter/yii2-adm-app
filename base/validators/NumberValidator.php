<?php

namespace app\base\validators;

use Yii;

/**
 * Class NumberValidator
 */
class NumberValidator extends \yii\validators\NumberValidator
{
    public $numberPattern = '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = str_replace(",", ".", $model->$attribute);
        parent::validateAttribute($model, $attribute);
    }
}
