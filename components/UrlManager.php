<?php

namespace app\components;

/**
 *
 */
class UrlManager extends \pavlinter\urlmanager\UrlManager
{
    public $enableLang = true;

    public $enablePrettyUrl = true;

    public $showScriptName = false;

    public $ruleConfig = [
        'class' => '\pavlinter\urlmanager\UrlRule',
    ];
}
