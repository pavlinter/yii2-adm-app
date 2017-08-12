<?php
namespace app\models\form;

use app\helpers\Html;
use app\helpers\Url;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Email required form
 */
class EmailRequiredForm extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => 'app\models\User',
                'filter' => function ($q) {
                    $q->andWhere([
                        'or',
                        ['status' => User::STATUS_ACTIVE],
                        ['status' => User::STATUS_DELETED],
                    ]);
                },
                'message' => Yii::t("app/emailRequired", "This username has already been taken. {loginBegin}login{loginEnd} {recBegin}recovery{recEnd}", [
                    'loginBegin' => Html::beginTag('a', ['href' => Url::to(['/site/login'])]),
                    'loginEnd' => Html::endTag('a'),
                    'recBegin' => Html::beginTag('a', ['href' => Url::to(['/site/request-password-reset'])]),
                    'recEnd' => Html::endTag('a'),
                ])
            ],
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
            'email' => Yii::t("model/emailRequired", "Email"),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        if (IS_LOCALHOST) {
            return true;
        } else {
            return Yii::$app->mailer->compose('emailRequired', ['user' => $this])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name ])
                ->setTo($this->email)
                ->setSubject(Yii::t("app/emailRequired", "User approval for {appName}", ['appName' => Yii::$app->name,'dot' => false]))
                ->send();
        }
    }
}
