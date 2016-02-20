<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets;

use Yii;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;


/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Captcha extends \yii\captcha\Captcha
{
    /**
     * Returns the options for the captcha JS widget.
     * @return array the options
     */
    public function getClientOptions()
    {
        $route = $this->captchaAction;
        if (is_array($route)) {
            $route[CaptchaAction::REFRESH_GET_VAR] = 1;
        } else {
            $route = [$route, CaptchaAction::REFRESH_GET_VAR => 1];
        }

        $options = [
            'refreshUrl' => Url::toRoute($route),
            //'hashKey' => "yiiCaptcha/{$route[0]}",
            'hashKey' => "yiiCaptcha/" . trim($route[0], '/'),
        ];

        return $options;
    }
}
