<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contact_msg}}".
 *
 * @property integer $id
 * @property string $from_email
 * @property string $to_email
 * @property string $subject
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class ContactMsg extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'attributes' => [
                    \yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => new \yii\db\Expression('NOW()')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact_msg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_email'], 'required'],
            [['text'], 'string'],
            [['from_email', 'to_email'], 'email'],
            [['subject'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model/contact_msg', 'ID'),
            'from_email' => Yii::t('model/contact_msg', 'From Email'),
            'to_email' => Yii::t('model/contact_msg', 'To Email'),
            'subject' => Yii::t('model/contact_msg', 'Subject'),
            'text' => Yii::t('model/contact_msg', 'Text'),
            'created_at' => Yii::t('model/contact_msg', 'Created At'),
            'updated_at' => Yii::t('model/contact_msg', 'Updated At'),
        ];
    }
}
