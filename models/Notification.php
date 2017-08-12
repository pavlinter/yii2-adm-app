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

    const TYPE_TICKET = 1;
    const TYPE_COMPLAINT = 2;

    const M_TICKET_REQUESTED = 0;
    const M_TICKET_OPENED = 1;
    const M_TICKET_MSG = 2;
    const M_TICKET_IGNORED = 3;
    const M_TICKET_CLOSED = 4;
    const M_COMPLAINT_CHECKED = 5;
    const M_COMPLAINT_CHECKED_BAD = 6;

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
				'class' => \yii\behaviors\TimestampBehavior::className(),
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
            //['category_id', 'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'],
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
     * @param $url
     * @param string $key
     * @return mixed
     */
    public function url($url = true, $key = 'alias')
    {
        if ($url === true) {
            $url = ['/admpages/default/index'];
        }
        return $url;
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
        return $this->hasOne(User::className(), ['id' => 'from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['id' => 'to_id']);
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function type_list($key = false, $default = null)
    {
        $list = [
            //static::TYPE_TICKET => Yii::t('model/notification/type', 'Ticket'),
            //static::TYPE_COMPLAINT => Yii::t('model/notification/type', 'Complaint'),
            static::TYPE_TICKET => 'Ticket',
            static::TYPE_COMPLAINT => 'Complaint',
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
            /*static::M_TICKET_REQUESTED => Yii::t('model/notification/msg-list', 'Ticket Requested', $data),
            static::M_TICKET_OPENED => Yii::t('model/notification/msg-list', 'Ticket Opened', $data),
            static::M_TICKET_MSG => Yii::t('model/notification/msg-list', 'Ticket Msg', $data),
            static::M_TICKET_IGNORED => Yii::t('model/notification/msg-list', 'Ticket Ignored', $data),
            static::M_TICKET_CLOSED => Yii::t('model/notification/msg-list', 'Ticket Closed', $data),
            static::M_COMPLAINT_CHECKED => Yii::t('model/notification/msg-list', 'Complaint Checked', $data),
            static::M_COMPLAINT_CHECKED_BAD => Yii::t('model/notification/msg-list', 'Complaint Checked Bad', $data),*/

            static::M_TICKET_REQUESTED => 'Ticket Requested',
            static::M_TICKET_OPENED => 'Ticket Opened',
            static::M_TICKET_MSG => 'Ticket Msg',
            static::M_TICKET_IGNORED => 'Ticket Ignored',
            static::M_TICKET_CLOSED => 'Ticket Closed',
            static::M_COMPLAINT_CHECKED => 'Complaint Checked',
            static::M_COMPLAINT_CHECKED_BAD => 'Complaint Checked Bad'

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
     * @param $row_id
     */
    public static function updateOwnViewed($from_user_id, $type, $row_id)
    {
        $command = Yii::$app->db->createCommand();
        $command->update(static::tableName(), [
            'viewed' => 1,
        ], [
            'from_id' => $from_user_id,
            'to_id' => Yii::$app->user->getId(),
            'type' => $type,
            'row_id' => $row_id,
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
            'row_id' => $row_id,
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
            if ($this->user_id !== Yii::$app->user->getId()) {
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
