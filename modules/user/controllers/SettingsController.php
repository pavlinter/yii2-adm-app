<?php

namespace app\modules\user\controllers;

use app\helpers\Url;
use app\models\form\UserSettings;

use app\models\Notification;
use app\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;


/**
 * Class SettingsController
 */
class SettingsController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \app\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionProfile()
    {
        $user_id = Yii::$app->user->getId();

        //Notification::add(1, Notification::T_MSG, 1, Notification::M_MSG, ['shortMsg' => 'Моё кароткое сообщение ....'], ['from_id' => 2]);
        //Notification::add(1, Notification::T_MSG, 2, Notification::M_MSG, ['shortMsg' => 'Моё привет сообщение ....'], ['from_id' => null]);
        //Notification::add(1, Notification::T_MSG, null, Notification::M_MSG, ['shortMsg' => 'Моё кароткое сообщение ....'], ['from_id' => 3]);

        for ($i = 0; $i < 3; $i++) {
            //Notification::add(1, Notification::T_MSG, null, Notification::M_MSG, ['shortMsg' => 'Моё привет сообщение ....'], ['from_id' => null]);
        }


        $model = UserSettings::find()->where(['id' => $user_id])->one();
        if ($model->load(Yii::$app->request->post())) {
            $requirements = \app\models\User::checkRequirements();
            if ($model->save()) {
                if ($requirements) {
                    return $this->goBack();
                }
                Yii::$app->session->setFlash('success', Yii::t("app", "Data successfully changed!", ['dot' => false]));
                return $this->refresh();
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionLoadNotification()
    {
        $json = [];
        $mode       = Yii::$app->request->post('mode', 'start');
        $lastId     = Yii::$app->request->post('lastId', 0);
        $firstId    = Yii::$app->request->post('firstId', 0);

        $msgItemsForPage = 10;
        $pages =  $msgItemsForPage + 1; //+1 что бы вычеслить есть ли предыдущие сообщения
        $params = [
            ':user_id' => Yii::$app->user->getId(),
        ];

        $tableName = Notification::tableName();
        $select = "n1.id, n1.from_id, n1.type, n1.row_id, n1.message, n1.data, n1.viewed, n1.created_at";

        if($mode == 'next'){

            $sql = "SELECT $select FROM $tableName n1
                JOIN (SELECT type, row_id, MAX(created_at) AS created FROM $tableName WHERE to_id=:user_id AND removed='0' GROUP BY type, row_id) n2
                ON n1.type = n2.type AND n1.created_at = n2.created
                WHERE id > :lastId AND n1.to_id=:user_id AND n1.removed='0' ORDER BY n1.created_at DESC
            ";
            $params[':lastId'] = $lastId;
        }elseif($mode == 'prev'){
            $sql = "SELECT $select FROM $tableName n1
                JOIN (SELECT type, row_id, MAX(created_at) AS created FROM $tableName WHERE to_id=:user_id AND removed='0' GROUP BY type, row_id) n2
                ON n1.type = n2.type AND n1.created_at = n2.created
                WHERE id < :firstId AND n1.to_id=:user_id AND n1.removed='0' ORDER BY n1.created_at DESC LIMIT $pages
            ";
            $params[':firstId'] = $firstId;
        }else{
            $sql = "SELECT $select FROM $tableName n1
                JOIN (SELECT type, row_id, MAX(created_at) AS created FROM $tableName WHERE to_id=:user_id AND removed='0' GROUP BY type, row_id) n2
                ON n1.type = n2.type AND n1.created_at = n2.created
                WHERE n1.to_id=:user_id AND n1.removed='0' ORDER BY n1.created_at DESC LIMIT $pages
            ";
        }

        $query = new ActiveQuery(Notification::class, [
            'sql' => $sql,
            'params' => $params,
            'with' => ['fromUser'],
        ]);

        $models = $query->all();
        $rows = [];
        /* @var $model Notification */
        foreach ($models as $i => $model) {
            /* @var $fromUser User */
            $fromUser = $model->fromUser;
            $data = [];
            if ($model->checkMessage([
                Notification::M_MSG,
            ])) {
                $data = [
                    //required
                    'id' => $model->id,
                    'template' => 'msg',
                    'unique' => hash('crc32b', $model->from_id . '-' . $model->type . '-' . $model->row_id),
                    'viewed' => (int)$model->viewed,
                ];
                //not required

                $data['msg'] = $model->getMessage();
                $data['date'] = Yii::$app->formatter->asTimeAgo($model->created_at);
                if ($model->from_id) {
                    $data['header'] = $fromUser->getAnonymous($model->from_id);
                    $data['href'] = Url::to(['/user/messages', 'id' => $model->row_id]);
                    $data['src'] = User::avatar($model->from_id);
                    $data['inlineHtml'] = User::onlineHtml($model->from_id);
                } else {
                    $data['icon'] = '<div class="icon-object bg-blue"><i class="fa fa-info"></i></div>';
                }



            }

            /*if (false) {
                $data = [
                    //required
                    'id' => $model->id,
                    'template' => 'check',
                    'unique' => hash('crc32b', $model->from_id . '-' . $model->type . '-' . $model->row_id),
                    'viewed' => (int)$model->viewed,

                    //not required
                    'href' => Url::to(['/complaint/view', 'id' => $model->row_id]),
                    'msg' => $model->getMessage(),
                    'date' => Yii::$app->formatter->asTimeAgo($model->created_at),
                    'icon' => $model->checkMessage(Notification::M_COMPLAINT_CHECKED) ? '<div class="icon-object bg-green"><i class="fa fa-plus"></i></div>' : '<div class="icon-object bg-red"><i class="fa fa-close"></i></div>',
                ];
            }*/


            if ($data) {
                $rows[$i] = $data;
            }
        }

        $prevLinkHidden = 0;
        $c_rows = sizeof($rows);
        if($c_rows){
            if(isset($rows[$c_rows - 2])){
                $firstId = $rows[$c_rows - 2]['id'];
                if($c_rows == $pages){
                    unset($rows[$c_rows - 1]);
                }else{
                    $prevLinkHidden = 1;
                }
            }else{
                $prevLinkHidden = 1;
                $firstId = $rows[$c_rows -1 ]['id'];
            }
            $lastId = $rows['0']['id'];
        }

        if($mode == 'next'){
            $rows = array_reverse($rows);
        }elseif($mode == 'prev'){

        }else{
            if(!$c_rows){
                $prevLinkHidden = 1;
            }
            $rows = array_reverse($rows);
        }

        $json['lastId'] = $lastId;
        $json['firstId'] = $firstId;
        $json['mode'] = $mode;
        $json['list'] = $rows;
        $json['prevLinkHidden'] = $prevLinkHidden;



        $sql = "SELECT COUNT(*) FROM $tableName n1
                JOIN (SELECT type, row_id, MAX(created_at) AS created FROM $tableName WHERE to_id=:user_id AND removed='0' AND viewed='0' GROUP BY type, row_id) n2
                ON n1.type = n2.type AND n1.created_at = n2.created
                WHERE n1.to_id=:user_id AND n1.removed='0' AND n1.viewed='0'";

        $json['count'] = Yii::$app->db->createCommand($sql, [':user_id' => Yii::$app->user->getId()])->queryScalar();
        return Json::encode($json);
    }

    /**
     * @return string
     */
    public function actionViewedNotification()
    {
        $json['r'] = false;
        $id = Yii::$app->request->post('id');

        /* @var $model Notification */
        $model = Notification::find()->pk($id)->one();
        if ($model) {
            if ($model->checkOwn()) {
                if ($model->viewed) {
                    $model->viewed = 0;
                } else {
                    $model->viewed = 1;
                    Notification::updateAll([
                        'viewed' => 1,
                    ], [
                        'to_id' => $model->to_id,
                        'type' => $model->type,
                        'row_id' => $model->row_id,
                    ]);
                }
                $model->save(false);
                $json['r'] = true;
                $json['viewed'] = $model->viewed;
            }
        }
        return Json::encode($json);
    }
}
