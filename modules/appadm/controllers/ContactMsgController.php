<?php

namespace app\modules\appadm\controllers;

use Yii;
use pavlinter\adm\Adm;
use app\models\ContactMsg;
use app\models\ContactMsgSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactMsgController implements the CRUD actions for ContactMsg model.
 */
class ContactMsgController extends Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \pavlinter\adm\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['AdmRoot', 'AdmAdmin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionViewed() {

        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $json['r'] = 1;
        if ($model->viewed) {
            $model->viewed = 0;
            $json['class'] = 'ic glyphicon glyphicon-remove text-danger';
        } else {
            $model->viewed = 1;
            $json['class'] = 'ic glyphicon glyphicon-ok text-success';
        }
        $model->save(false);

        return Json::encode($json);
    }

    /**
     * Lists all ContactMsg models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactMsgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single ContactMsg model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->viewed == 0) {
            $model->viewed = 1;
            $model->save(false);
            return $this->refresh();
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ContactMsg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContactMsg();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully inserted!'));
            return Adm::redirect(['update', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContactMsg model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully changed!'));
            return Adm::redirect(['update', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ContactMsg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully removed!'));
        return Adm::goBack(['index']);
    }

    /**
     * Finds the ContactMsg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContactMsg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContactMsg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
