<?php

namespace app\modules\profilelogin\controllers;

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
        $user = User::findByUsername($username);

        if(Yii::$app->user->login($user, 0)){
            return $this->redirect(['/adm/user/update']);
        }
        return Adm::goBack(['/adm/user/update']);
    }
}
