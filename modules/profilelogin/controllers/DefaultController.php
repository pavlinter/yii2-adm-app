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
     * @param $id
     * @return \yii\web\Response
     */
    public function actionLogin($id)
    {
        /* @var $user User */
        $user = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
        Yii::$app->user->login($user, 0);
        Yii::$app->session->set('AdmSpy', $user->id);
        if (Yii::$app->user->can('AdmRoot')) {
            return $this->redirect(['/adm/user/update']);
        }
        return $this->redirect(Url::getLangUrl());
    }
}
