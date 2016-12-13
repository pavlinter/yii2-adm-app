<?php

namespace app\components;

use Yii;

/**
 * Class Formatter
 */
class Formatter extends \yii\i18n\Formatter
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->timeZone === null) {
            $this->timeZone = Yii::$app->timeZone;
            $this->defaultTimeZone = Yii::$app->timeZone;
        }
        parent::init();
    }

    /**
     * @param $price
     * @param int|null $decimal
     * @param array $options
     * @param array $textOptions
     * @return string
     */
    public function asPrice($price, $decimal = 2, $options = [], $textOptions = [])
    {
        return "€" . $this->asDecimal($price, $decimal, $options, $textOptions);
    }
}
