<?php
namespace app\models\form;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        if (IS_LOCALHOST) {
            $verifyCode = ['verifyCode', 'captcha'];
        } else {
            $verifyCode = [['verifyCode'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::class];
        }

        return [
            ['password', 'filter', 'filter' => 'trim'],
            ['username', 'filter', 'filter' => function ($value) {
                return strtolower(trim($value));
            }],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => Yii::t("model/signup", "This username has already been taken.")],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => Yii::t("model/signup", "This email address has already been taken.")],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            $verifyCode,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t("model/signup", "Username"),
            'email' => Yii::t("model/signup", "Email"),
            'password' => Yii::t("model/signup", "Password"),
            'verifyCode' => Yii::t("model/signup", "Verification Code"),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            if ($user->save()) {

                $auth = Yii::$app->authManager;
                $newRole = $auth->createRole('user');
                $auth->assign($newRole, $user->id);

                if (IS_LOCALHOST) {
                    $html = Yii::$app->controller->renderPartial('@app/mail/userApproval', ['user' => $user]);
                    Yii::$app->getSession()->setFlash('info', $html);
                    return $user;
                } else {
                    return Yii::$app->mailer->compose('userApproval', ['user' => $user])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name ])
                        ->setTo($this->email)
                        ->setSubject(Yii::t("app/signup", "User approval for {appName}", ['appName' => Yii::$app->name,'dot' => false]))
                        ->send();
                }


            } else {
                return null;
            }
            return $user;
        }

        return null;
    }
}
