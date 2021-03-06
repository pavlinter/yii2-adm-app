<?php

namespace app\modules\admlivechat\controllers;

use app\modules\admlivechat\Module;
use pavlinter\adm\Adm;
use pavlinter\adm\filters\AccessControl;
use Yii;
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
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['AdmRoot', 'AdmAdmin'],
                    ],
                ],
            ],
        ];
    }
    /**
     * @return string
     */
    public function actionIndex()
    {
        $module = Module::getInstance();

        /* @var $model \app\modules\admlivechat\models\SettingsForm */
        $model = $module->manager->createSettingsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully changed!'));
                return Adm::redirect(['index']);
            }
        }

        return $this->render('index',[
            'model' => $model,
        ]);
    }

}
