<?php

namespace app\models;

use app\base\ModelArrayableTrait;
use Yii;

/**
 * User model
 */
class User extends \pavlinter\adm\models\User
{
    use ModelArrayableTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'unique'],
            [['email'], 'email'],

            ['status', 'default', 'value' => static::STATUS_NOT_APPROVED],
            ['status', 'in', 'range' => array_keys(static::status_list())],

            ['role', 'default', 'value' => static::ROLE_USER],
            ['role', 'in', 'range' => array_keys(static::roles_list())],
        ];
    }
}
