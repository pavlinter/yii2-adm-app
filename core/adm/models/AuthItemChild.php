<?php

namespace app\core\adm\models;

use app\base\ModelArrayableTrait;
use Yii;

/**
 * This is the model class for table "adm_auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $itemParent
 * @property AuthItem $itemChild
 */
class AuthItemChild extends \pavlinter\adm\models\AuthItemChild
{
    use ModelArrayableTrait;
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemParent()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }
}
