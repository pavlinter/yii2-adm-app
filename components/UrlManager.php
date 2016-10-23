<?php

namespace app\components;

/**
 * Class UrlManager
 */
class UrlManager extends \pavlinter\urlmanager\UrlManager
{
    public $enableLang = true;

    public $enablePrettyUrl = true;

    public $showScriptName = false;

    public $ruleConfig = [
        'class' => '\pavlinter\urlmanager\UrlRule',
    ];

    public $normalizer = [
        'class' => 'yii\web\UrlNormalizer',
    ];
}
