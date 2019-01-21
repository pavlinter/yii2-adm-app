<?php

namespace app\models;

use app\helpers\Html;
use app\models\query\NotificationQuery;
use yii\helpers\ArrayHelper;
use app\helpers\Url;
use app\base\ModelArrayableTrait;
use Yii;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property integer $id
 * @property string $from_id
 * @property string $to_id
 * @property integer $type
 * @property string $row_id
 * @property string $message
 * @property string $data
 * @property integer $viewed
 * @property integer $removed
 * @property string $created_at
 * @property string $updated_at
 *
 * Relations
 * @property User $fromUser
 * @property User $toUser
 */
class Notification extends \yii\db\ActiveRecord
{
    use ModelArrayableTrait;

    const T_MSG = 1;

    const M_MSG = 1;

    /**
     * @inheritdoc
     * @return NotificationQuery
     */
    public static function find()
    {
        return new NotificationQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			[
				'class' => \yii\behaviors\TimestampBehavior::class,
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
        return '{{%notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_id', 'to_id', 'row_id'], 'integer'],

            [['viewed', 'removed'], 'boolean'],
            ['type', 'in', 'range' => array_keys(static::type_list())],
            ['message', 'in', 'range' => array_keys(static::message_list())],
            //['data', 'each', 'rule' => ['string']],
            ['data', 'safe'],
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
            'id' => Yii::t('model/notification', 'ID'),
            'from_id' => Yii::t('model/notification', 'From'),
            'to_id' => Yii::t('model/notification', 'To'),
            'type' => Yii::t('model/notification', 'Type'),
            'row_id' => Yii::t('model/notification', 'Row'),
            'message' => Yii::t('model/notification', 'Message'),
            'data' => Yii::t('model/notification', 'Data'),
            'viewed' => Yii::t('model/notification', 'Viewed'),
            'removed' => Yii::t('model/notification', 'Removed'),
            'created_at' => Yii::t('model/notification', 'Created'),
            'updated_at' => Yii::t('model/notification', 'Updated'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (is_array($this->data) || is_object($this->data)) {
            $this->data = serialize($this->data);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $data = @unserialize($this->data);
        if ($data !== false) {
            $this->data = $data;
        }
    }

    /**
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    public function urlTo($scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'url' => true,
            'key' => 'alias',
        ], $options);
        return Url::to($this->url($options['url'], $options['key']), $scheme);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::class, ['id' => 'from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::class, ['id' => 'to_id']);
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function type_list($key = false, $default = null)
    {
        $list = [
            static::T_MSG => Yii::t('model/notification/type', 'Msg'),
        ];
        if ($key !== false) {
            if (isset($list[$key])) {
                return $list[$key];
            }
            return $default;
        }
        return $list;
    }


    /**
     * @param array $params
     * @return string
     */
    public function getMessage($params = [])
    {
        $params = array_merge($this->data, $params);
        return static::message_list($this->message, null, $params);
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function message_list($key = false, $default = null, $data = [])
    {
        $list = [
            static::M_MSG => Yii::t('model/notification/msg-list', 'Message', $data),
        ];
        if ($key !== false) {
            if (isset($list[$key])) {
                return $list[$key];
            }
            return $default;
        }
        return $list;
    }

    /**
     * @param $from_user_id
     * @param $type
     * @param null $row_id
     */
    public static function updateOwnViewed($from_user_id, $type, $row_id = null)
    {
        $command = Yii::$app->db->createCommand();
        $command->update(static::tableName(), [
            'viewed' => 1,
        ], [
            'from_id' => $from_user_id,
            'to_id' => Yii::$app->user->getId(),
            'type' => $type,
            'row_id' => ($row_id ? $row_id : 0),
            'viewed' => 0,
        ])->execute();
    }

    /**
     * @param $to_user_id
     * @param $type_id
     * @param $row_id
     * @param $message_id
     * @param $data
     * @param array $options
     * @return mixed
     */
    public static function add($to_user_id, $type_id, $row_id, $message_id, $data = [], $options = [])
    {
        $from_id = ArrayHelper::remove($options, 'from_id', Yii::$app->user->getId());
        $model = new static;
        $data = [
            'from_id' => $from_id,
            'to_id' => $to_user_id,
            'type' => $type_id,
            'row_id' => ($row_id ? $row_id : 0),
            'message' => $message_id,
            'data' => $data,
        ];
        if (!isset($data['data']['gender'])) {
            $data['data']['gender'] = User::identity()->gender;
        }
        $model->load($data, '');
        return $model->save();
    }

    /**
     * @param $to_user_id
     * @param $type_id
     * @param $row_id
     * @param $message_id
     * @param $data
     * @param array $options
     * @return mixed
     */
    public static function addAdmin($to_user_id, $type_id, $row_id, $message_id, $data = [], $options = [])
    {
        $options['from_id'] = null;
        return static::add($to_user_id, $type_id, $row_id, $message_id, $data, $options);
    }


    /**
     * @param $message
     * @return bool
     */
    public function checkMessage($message)
    {
        $message = (array)$message;
        if (!in_array($this->message, $message)) {
            return false;
        }
        return true;
    }

    /**
     * @param bool $exception
     * @return bool
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkOwn($exception = false)
    {
        if ($exception) {
            if ($this->to_id !== Yii::$app->user->getId()) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page.');
            }
        } else {
            if ($this->to_id !== Yii::$app->user->getId()) {
                return false;
            }
        }
        return true;
    }
}
