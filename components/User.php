<?php

namespace app\components;

use Yii;

/**
 * User is the class for the "user" application component that manages the user authentication status.
 */
class User extends \yii\web\User
{
    /**
     * @param string $permissionName
     * @param array $params
     * @param bool|true $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if (!Yii::$app->user->isGuest) {
            return parent::can($permissionName, $params, $allowCaching);
        }
    }
}
