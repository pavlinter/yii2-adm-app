<?php

namespace app\modules\profilelogin\controllers;

use app\helpers\Url;
use app\models\User;
use pavlinter\adm\Adm;
use pavlinter\adm\filters\AccessControl;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['AdmRoot'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionLogin($username)
    {
        /* @var $user User */
        $user = User::findByUsername($username);
        Yii::$app->user->login($user, 0);
        if (Yii::$app->user->can('AdmRoot') || Yii::$app->user->can('AdmAdmin')) {
            return $this->redirect(['/adm/user/update']);
        }
        return $this->redirect(Url::getLangUrl());
    }
}
