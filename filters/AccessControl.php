<?php

namespace app\filters;

use app\components\User;
use Yii;
use yii\di\Instance;

/**
 * Class AccessControl
 */
class AccessControl extends \yii\filters\AccessControl
{
    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, User::class);
        foreach ($this->rules as $i => $rule) {
            if (is_array($rule)) {
                $this->rules[$i] = Yii::createObject(array_merge($this->ruleConfig, $rule));
            }
        }
    }
}