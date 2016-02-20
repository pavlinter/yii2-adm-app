<?php

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use yii\web\Controller;

/**
 * Pages controller
 */
class PagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [];
    }

    /**
     * @param null|\app\core\admpages\models\Page $modelPage
     * @return string|\yii\web\Response
     */
    public function actionContact($modelPage = null)
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t("app/contacts", "Thank you for contacting us. We will respond to you as soon as possible.", ['dot' => false]));
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', Yii::t("app/contacts", "There was an error sending email.", ['dot' => false]));
        }
        return $this->render('contact', [
            'model' => $model,
            'modelPage' => $modelPage,
        ]);
    }
}
