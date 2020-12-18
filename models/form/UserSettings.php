<?php

namespace app\models\form;

use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $gender
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $social_type
 * @property integer $social_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class UserSettings extends \app\models\User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => function ($value) {
                return strtolower(trim($value));
            }],
            [['username' ,'firstname', 'lastname'], 'filter', 'filter' => function ($value) {
                return \app\helpers\Html::encode(trim($value));
            }],
            ['username', 'unique',
                'targetClass' => 'app\models\User',
                'message' => Yii::t("model/signup", "This username has already been taken."),
                'filter' => function ($query) {
                    /* @var $query \yii\db\Query */
                    $query->andWhere(['!=', 'id', Yii::$app->user->getId()]);
                },
            ],
            [['username' ,'firstname', 'lastname', 'gender', 'display_type'], 'required'],
            //[['email'], 'email'],
            ['gender', 'in', 'range' => array_keys(static::gender_list())],
            ['display_type', 'in', 'range' => array_keys(static::display_type_list())],
            [['firstname', 'lastname'], 'string', 'max' => 100],
        ];
    }

}
