<?php

namespace app\core\adm\models;

use app\base\ModelArrayableTrait;
use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $id
 * @property integer $language_id
 * @property string $translation
 *
 * @property SourceMessage $sourceMessage
 */
class Message extends \pavlinter\adm\models\Message
{
    use ModelArrayableTrait;
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
