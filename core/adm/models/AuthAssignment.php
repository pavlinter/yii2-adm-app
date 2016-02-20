<?php

namespace app\core\adm\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "adm_auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 * @property User $user
 */
class AuthAssignment extends \pavlinter\adm\models\AuthAssignment
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
