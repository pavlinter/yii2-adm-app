<?php

namespace app\core\adm\models;

use app\base\ModelArrayableTrait;
use Yii;


/**
 * This is the model class for table "adm_auth_rule".
 *
 * @property string $name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends \pavlinter\adm\models\AuthRule
{
    use ModelArrayableTrait;
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::class, ['rule_name' => 'name']);
    }
}
