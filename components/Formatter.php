<?php

namespace app\components;

use Yii;

/**
 * Class Formatter
 */
class Formatter extends \yii\i18n\Formatter
{
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
