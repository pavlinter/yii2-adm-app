<?php

namespace app\models;

use app\base\ModelArrayableTrait;
use app\helpers\ArrayHelper;
use Yii;
use yii\db\Expression;
use yii\helpers\StringHelper;

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
 * @property integer $display_type
 * @property integer $social_type
 * @property integer $social_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $online
 * @property integer $cash
 */
class User extends \pavlinter\adm\models\User
{
    use ModelArrayableTrait;

    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const ROLE_SUBSCRIBE = 9;

    const SOCIAL_TYPE_FACEBOOK = 1;

    const DIS_TYPE_USERNAME = 1;
    const DIS_TYPE_FIRSTNAME = 2;
    const DIS_TYPE_FIRSTN_LASTN = 3;
    const DIS_TYPE_FIRSTN_L = 4;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model/user', 'id'),
            'username' => Yii::t('model/user', 'Username'),
            'firstname' => Yii::t('model/user', 'Firstname'),
            'lastname' => Yii::t('model/user', 'Lastname'),
            'gender' => Yii::t('model/user', 'Gender'),
            'display_type' => Yii::t('model/user', 'Display Type'),
            'social_type' => Yii::t('model/user', 'Social Type'),
            'social_id' => Yii::t('model/user', 'Social Id'),
            'email' => Yii::t('model/user', 'Email'),
            'role' => Yii::t('model/user', 'Role'),
            'status' => Yii::t('model/user', 'Status'),
            'cash' => Yii::t('model/user', 'Cash'),
            'online' => Yii::t('model/user', 'Online'),
            'created_at' => Yii::t('model/user', 'Created'),
            'updated_at' => Yii::t('model/user', 'Updated'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => function ($value) {
                return strtolower(trim($value));
            }],

            [['firstname', 'lastname'], 'filter', 'filter' => function ($value) {
                return trim($value);
            }],

            [['username', 'email', 'firstname', 'gender'], 'required', 'on' => ['client-update']],
            [['username'], 'unique'],
            [['email'], 'email'],

            ['status', 'default', 'value' => self::STATUS_NOT_APPROVED],
            ['status', 'in', 'range' => array_keys(static::status())],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => array_keys(static::roles())],
            ['gender', 'in', 'range' => array_keys(static::gender_list())],

            ['display_type', 'in', 'range' => array_keys(static::display_type_list())],


            [['firstname', 'lastname'], 'string', 'max' => 50],
            [['cash'], 'number'],
            [['online'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss', 'isEmpty' => function ($value) {
                if (!$value) {
                    $this->online = date('Y-m-d H:i:s');
                }
                return false;
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['adm-insert'] = ['username', 'email', 'status', 'role', 'firstname', 'lastname', 'gender', 'display_type', 'cash', 'online'];
        $scenarios['adm-updateOwn'] = ['username', 'email', 'firstname', 'lastname', 'gender', 'display_type', 'cash', 'online'];
        $scenarios['adm-update'] = ['username', 'email', 'status', 'role', 'firstname', 'lastname', 'gender', 'display_type', 'cash', 'online'];
        return $scenarios;
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function gender_list($key = false, $default = null)
    {
        $list = [
            static::GENDER_FEMALE => Yii::t('model/user', 'Female', ['dot' => false]),
            static::GENDER_MALE => Yii::t('model/user', 'Male', ['dot' => false]),
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
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function display_type_list($key = false, $default = null)
    {
        $list = [
            //static::DIS_TYPE_USERNAME => Yii::t('model/user/display_type', 'Username', ['dot' => false]),
            static::DIS_TYPE_FIRSTN_L => Yii::t('model/user/display_type', 'Firstname L.', ['dot' => false]),
            static::DIS_TYPE_FIRSTNAME => Yii::t('model/user/display_type', 'Firstname', ['dot' => false]),
            static::DIS_TYPE_FIRSTN_LASTN => Yii::t('model/user/display_type', 'Firstname Lastname', ['dot' => false]),
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
     * @param null $key
     * @param null $default
     * @return array|null
     */
    public static function status($key = null, $default = null)
    {
        $status = [
            self::STATUS_ACTIVE => Yii::t('model/user', 'Active Status', ['dot' => false]),
            self::STATUS_NOT_APPROVED => Yii::t('model/user', 'Not Approved Status', ['dot' => false]),
            self::STATUS_DELETED => Yii::t('model/user', 'Deleted Status', ['dot' => false]),
        ];
        if ($key !== null) {
            if (isset($status[$key])) {
                return $status[$key];
            }
            return $default;
        }

        return $status;
    }

    /**
     * @param null $key
     * @param null $default
     * @return array|null
     */
    public static function roles($key = null, $default = null)
    {
        $roles = [
            self::ROLE_USER => Yii::t('model/user', 'User Role', ['dot' => false]),
            self::ROLE_ADM  => Yii::t('model/user', 'Adm Role', ['dot' => false]),
            self::ROLE_SUBSCRIBE  => Yii::t('model/user', 'Subscribe Role', ['dot' => false]),
        ];
        if ($key !== null) {
            if (isset($roles[$key])) {
                return $roles[$key];
            }
            return $default;
        }

        return $roles;
    }

    /**
     * Finds network user
     * @param $social_id
     * @param $social_type
     * @return $this
     */
    public static function findBySocial($social_id, $social_type)
    {
        return static::findOne(['social_id' => $social_id, 'social_type' => $social_type]);
    }


    /**
     * @param int $size
     * @param array $options
     * @return mixed|null
     */
    public static function ownAvatar($size = 128 , $options = [])
    {
        return static::avatar(Yii::$app->user->getId(), $size, $options);
    }

    /**
     * @param $user_id
     * @param int $size
     * @param array $options
     * @return mixed|null
     */
    public static function avatar($user_id, $size = 360 , $options = [])
    {
        if (is_array($size)) {
            $options = ArrayHelper::merge([
                'width' => 360,
                'height' => 360,
                'mode' => \pavlinter\display2\objects\Image::MODE_OUTBOUND,
            ], $size);
        } else {
            $options['width'] = $options['height'] = $size;
            $options['mode'] = \pavlinter\display2\objects\Image::MODE_OUTBOUND;
        }

        $return = ArrayHelper::remove($options, 'return');

        $data = Yii::$app->display->getFileImg($user_id, 'user', $options, [
            'dir' => 'main',
        ]);

        if ($return) {
            return $data;
        }
        return $data['display'];

    }

    /**
     * @return null|\app\models\User
     */
    public static function identity()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return bool
     */
    public static function checkRequirements()
    {
        if (!Yii::$app->user->isGuest) {
            //если существуют обязательные поля
            $firstname = static::identity()->firstname;
            if ($firstname == null || $firstname == '') {
                return true;  //редирект на профайл
            }
        }
        return false;
    }


    /**
     * @param null $from_user_id
     * @return array|null
     */
    public function getAnonymous($from_user_id = null)
    {
        //везде где скрываеться имя
        if ($from_user_id === null) {
            $from_user_id = $this->id;
        }
        return $this->getField('display');
    }

    /**
     * @param $user_id
     * @return boolean
     */
    public static function online($user_id)
    {
        return Yii::$app->cache->get('online_' . $user_id) == 1;
    }

    /**
     * @param $user_id
     * @param string $class
     * @return boolean
     */
    public static function onlineHtml($user_id, $class = 'online-abs')
    {
        $options = ['class' => $class . ' online-' . $user_id];
        if (static::online($user_id)) {
            \app\helpers\Html::addCssClass($options, 'active');
        }
        return \app\helpers\Html::tag('span', null, $options);
    }

    /**
     *
     */
    public static function setOnline()
    {
        $user_id = Yii::$app->user->getId();
        $online = static::online($user_id);
        if($online === false)
        {
            $admSpy = Yii::$app->session->get('AdmSpy');
            if ($admSpy == $user_id) {
                return null;
            }

            $duration = Yii::$app->params['user.online'] * 60;
            static::updateAll([
                'online' => new Expression('ADDDATE(NOW(),INTERVAL :seconds SECOND)'),
            ], [
                'id' => $user_id,
            ], [
                ':seconds' => $duration
            ]);
            Yii::$app->cache->set('online_' . $user_id, 1, $duration);
        }
    }

    /**
     *
     */
    public static function clearOnline()
    {
        Yii::$app->session->remove('AdmSpy'); // for admin
        Yii::$app->cache->delete('online_' . Yii::$app->user->getId());
    }

    /**
     * @return array
     */
    public function fields()
    {
        $fields = $this->traitFields();

        $fields['social_link'] = function(){
            if ($this->social_type == static::SOCIAL_TYPE_FACEBOOK && $this->social_id) {
                return 'https://www.facebook.com/app_scoped_user_id/' . $this->social_id . '/';
            }
            return false;
        };


        $fields['anonymous'] = function () {
           return $this->username;
        };

        $fields['display'] = function(){

            if (static::checkRequirements()) {
                return $this->username;
            }

            if ($this->display_type == static::DIS_TYPE_FIRSTN_L) {
                return ucfirst($this->firstname) . ' ' . ucfirst(StringHelper::truncate($this->lastname, 1, '.'));
            } else if ($this->display_type == static::DIS_TYPE_FIRSTN_LASTN) {
                return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
            } else if ($this->display_type == static::DIS_TYPE_FIRSTNAME) {
                return ucfirst($this->firstname);
            }
            // else static::DIS_TYPE_USERNAME

            return $this->username;
        };

        return $fields;
    }


}
