<?php

namespace app\controllers;

use app\core\admpages\models\Page;
use app\helpers\Html;
use app\helpers\Url;
use app\models\form\EmailRequiredForm;
use app\models\form\LoginForm;
use app\models\form\PasswordResetRequestForm;
use app\models\form\ResetPasswordForm;
use app\models\form\SignupForm;
use app\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Class SiteController
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            */
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0xf59c1a,
                'transparent' => true,
                'minLength' => 6,
                'maxLength' => 7,
                'fixedVerifyCode' => YII_ENV_TEST || IS_LOCALHOST ? '1' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (\app\models\User::checkRequirements()) {
                Yii::$app->session->setFlash('danger', Yii::t("app", "You need to finish your profile!", ['dot' => false]));
                return $this->redirect(['/user/settings/profile']);
            }
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        User::clearOnline();
        Yii::$app->user->logout(false);
        return $this->redirect(Url::getLangUrl());
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            /* @var $user User */
            
            $user = $model->signup();
            if ($user) {
                Yii::$app->getSession()->setFlash('success', Yii::t("app/signup", "Check your email for further instructions."));
                if (IS_LOCALHOST) {
                    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/user-approve', 'token' => $user->password_reset_token]);
                    $msg = Yii::t("app/signup", "Hello {username}, <br />Follow the link below to approve your account:<br />{link}", ['username' => \yii\helpers\Html::encode($user->username), 'link' => \yii\helpers\Html::a(\yii\helpers\Html::encode('link'), $resetLink)]);
                    Yii::$app->getSession()->setFlash('info', $msg);
                }
                return $this->refresh();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t("app/passwordReset", "Check your email for further instructions."));
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t("app/passwordReset", "Sorry, we are unable to reset password for email provided."));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t("app/passwordReset", "New password was saved."));
            return $this->redirect(['login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    /**
     * @param $token
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionUserApprove($token)
    {
        $type = 'info';
        $message = Yii::t("app/signup", "Your account already approved");

        if (!empty($token)) {
            /* @var $user User */
            $user = User::findOne([
                'password_reset_token' => $token,
                'status' => User::STATUS_NOT_APPROVED,
            ]);

            if ($user) {
                $user->status = User::STATUS_ACTIVE;
                $user->removePasswordResetToken();
                if ($user->save(false)) {
                    $auth_id = Yii::$app->session->remove('auth_id');
                    $auth_type = Yii::$app->session->remove('auth_type');
                    $message = Yii::t("app/signup", "Your account successfully approved!");
                    Yii::$app->getSession()->setFlash('success', $message);
                    if ($auth_id && $auth_type) {
                        if (Yii::$app->getUser()->login($user)) {
                            return $this->redirect(['/user/settings/profile']);
                        }
                    }
                    return $this->redirect(['login']);
                } else {
                    Yii::error('User(#' . $user->id . ') approved status is not saved');
                    $message = Yii::t("app/signup", "Oops, something went wrong. Please try again later.");
                    $type = 'danger';
                }
            }
        }

        return $this->render('user-approve',[
            'type' => $type,
            'message' => $message,
        ]);
    }

    /**
     * @param $client \yii\authclient\OAuth2
     * @return string
     */
    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        /*
            [
                'id' => '12121212'
                'name' => 'Pavel Ivanov'
                'email' => 'pavter@gmail.com'
                'first_name' => 'Pavel'
                'last_name' => 'Ivanov'
                'age_range' => [
                    'min' => 21
                ]
                'link' => 'https://www.facebook.com/app_scoped_user_id/18038451212/'
                'gender' => 'male'
                'locale' => 'ru_RU'
                'picture' => [
                    'data' => [
                        'is_silhouette' => false
                        'url' => 'https://scontent.xx.fbcdn.net/v/t1.0-1/p50x50/10408119_1614291085467667_2676545651212183_n.jpg?oh=bed8f9e4589bad96bc083cb1dcbdc89f&oe=57CCFF65' //50x50
                    ]
                ]
                'timezone' => 3
                'updated_time' => '2016-02-19T19:20:51+0000'
                'verified' => true
            ]
        */
        $user = User::findBySocial($attributes['id'], User::SOCIAL_TYPE_FACEBOOK);

        $redirect = Yii::$app->getUser()->getReturnUrl(['/user/settings/profile']);
        $emailExists = false;
        if ($user) {
            if ($user->status == User::STATUS_ACTIVE) {
                Yii::$app->user->switchIdentity($user ,3600 * 24 * 30);
            } else {
                Yii::$app->session->set('auth_id', $attributes['id']);
                Yii::$app->session->set('auth_type', User::SOCIAL_TYPE_FACEBOOK);
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                }
                $user->save(false);
                $redirect = Url::to(['/site/email-required']);
            }
        } else {

            $user = new User();
            $user->username = "fb" . $attributes['id'];
            $user->social_type = User::SOCIAL_TYPE_FACEBOOK;
            $user->social_id = $attributes['id'];
            $user->firstname = $attributes['first_name'];
            $user->lastname = $attributes['last_name'];
            $emailRequired = false;

            if (isset($attributes['email'])) {

                $emailExists = User::find()->where(['email' => $attributes['email']])->exists();
                if (!$emailExists) {
                    $user->status = User::STATUS_ACTIVE;
                    $user->email = $attributes['email'];
                }


            } else {
                $user->status = User::STATUS_NOT_APPROVED;
                Yii::$app->session->set('auth_id', $attributes['id']);
                Yii::$app->session->set('auth_type', User::SOCIAL_TYPE_FACEBOOK);
                $emailRequired = true;
            }

            if (!$emailExists) {
                if ($attributes['gender'] == 'female') {
                    $user->gender = User::GENDER_FEMALE;
                } else if($attributes['gender'] == 'male'){
                    $user->gender = User::GENDER_MALE;
                } else {
                    $user->gender = null;
                }

                $user->setPassword(md5($attributes['id']));
                $user->generateAuthKey();
                if ($user->save()) {
                    //get main photo
                    $response = $client->api('me/picture', 'GET', [
                        'type' => 'large',
                        'redirect' => false,
                    ]);
                    if (isset($response['data']) && isset($response['data']['url']) && $response['data']['url']) {
                        $photoUrl = $response['data']['url'];
                        $fileParts = pathinfo($photoUrl);
                        $dir = Yii::getAlias('@webroot/files/data/user/' . $user->id . '/main/');
                        FileHelper::createDirectory($dir);
                        //remove GET params
                        $extension = explode('?', $fileParts['extension'])['0'];
                        if (Yii::$app->display->supported($extension)) {
                            copy($photoUrl, $dir . 'main.' . $extension);
                        }
                    }

                    if ($emailRequired) {
                        $redirect = Url::to(['/site/email-required']);
                    } else {
                        if (Yii::$app->getUser()->login($user)) {
                            $redirect = Url::to(['/user/settings/profile']);
                        }
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('info', Yii::t("app/authclient", "Email already exist. Try recovery accaunt!", [
                    'loginBegin' => Html::beginTag('a', ['href' => Url::to(['/site/login'])]),
                    'loginEnd' => Html::endTag('a'),
                    'recBegin' => Html::beginTag('a', ['href' => Url::to(['/site/request-password-reset'])]),
                    'recEnd' => Html::endTag('a'),
                ]));
                $redirect = Url::to(['/site/login']);
            }
        }

        $this->layout = '/base';
        $result = $this->render('auth', [
            'redirect' => $redirect,
            'emailExists' => $emailExists,
        ]);
        $response = Yii::$app->getResponse();
        if ($result !== null) {
            $response->data = $result;
        }
        return $response;
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionEmailRequired()
    {
        $id = Yii::$app->session->get('auth_id');
        $type = Yii::$app->session->get('auth_type');
        if (!$id || !$type) {
            throw new \yii\web\NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        $showOnlyAlert = false;
        /* @var $model EmailRequiredForm */
        $model = EmailRequiredForm::findBySocial($id, $type);
        

        
        if (!$model) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app/emailRequired', 'User not found.'));
        }

        if ($model->status === User::STATUS_NOT_APPROVED) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (!User::isPasswordResetTokenValid($model->password_reset_token)) {
                    $model->generatePasswordResetToken();
                }
                if ($model->save(false) && $model->sendEmail()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t("app/emailRequired", "Check your email for further instructions."));
                    return $this->refresh();
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t("app", "Oops, something went wrong. Please try again!"));
                }
            }
        } elseif($model->status === User::STATUS_DELETED){
            Yii::$app->getSession()->setFlash('info', Yii::t("app/emailRequired", "User already deleted."));
            $showOnlyAlert = true;
        } else {
            Yii::$app->getSession()->setFlash('info', Yii::t("app/emailRequired", "User already activated."));
            $showOnlyAlert = true;
        }

        return $this->render('email-required', [
            'model' => $model,
            'showOnlyAlert' => $showOnlyAlert,
        ]);
    }

}
