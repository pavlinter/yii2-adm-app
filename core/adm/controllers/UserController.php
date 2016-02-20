<?php

namespace app\core\adm\controllers;

use pavlinter\adm\Adm;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends \pavlinter\adm\controllers\UserController
{
    public $excludeRole = [
        'Adm-UpdateOwnUser',
        'Adm-Transl',
        'Adm-Transl:Html',
    ];

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Adm::getInstance()->manager->createUser();
        $model->setScenario('adm-insert');

        $dynamicModel = DynamicModel::validateData(['password', 'password2', 'assignment'], [
            [['password', 'password2'], 'required'],
            [['password', 'password2'], 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password'],
        ]);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $dynamicModel->load($post)) {
            if ($model->validate() && $dynamicModel->validate()) {
                $model->setPassword($dynamicModel->password);



                if ($model->save(false)) {
                    if (!Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {
                        //AdmRoot
                        $auth = Yii::$app->authManager;
                        $roles = Yii::$app->request->post('roles', []);
                        $auth->revokeAll($model->id); //remove all assignments

                        if(in_array('AdmRoot', $roles) || in_array('AdmAdmin', $roles)){
                            $model->role = \app\models\User::ROLE_ADM;
                        } else {
                            $model->role = \app\models\User::ROLE_USER;
                        }
                        foreach ($roles as $role) {
                            $newRole = $auth->createRole($role);
                            $auth->assign($newRole, $model->id);
                        }
                    }
                    $model->save(false);
                    Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully inserted!'));
                    return Adm::redirect(['update', 'id' => $model->id]);
                }
            }
        }

        $authItems = Adm::getInstance()->manager->createAuthItemQuery('find')->select(['name'])->where(['type' => Item::TYPE_ROLE])->all();

        return $this->render('create', [
            'model' => $model,
            'dynamicModel' => $dynamicModel,
            'authItems' => $authItems,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id = null)
    {
        if ($id === null) {
            $id = Adm::getInstance()->user->getId();
        }
        /* @var $model \pavlinter\adm\models\User */
        $model = $this->findModel($id);

        if (Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {
            $model->setScenario('adm-updateOwn');
        } elseif (Adm::getInstance()->user->can('AdmRoot')) {
            $model->setScenario('adm-update');
        } else {
            throw new ForbiddenHttpException('Access denied');
        }

        $dynamicModel = DynamicModel::validateData(['password', 'password2'], [
            [['password', 'password2'], 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password'],
        ]);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $dynamicModel->load($post)) {


            if ($model->validate() && $dynamicModel->validate()) {
                if (!empty($dynamicModel->password)) {
                    $model->setPassword($dynamicModel->password);
                }

                if (!Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {
                    //AdmRoot
                    $auth = Yii::$app->authManager;
                    $roles = Yii::$app->request->post('roles', []);
                    $auth->revokeAll($model->id); //remove all assignments

                    if(in_array('AdmRoot', $roles) || in_array('AdmAdmin', $roles)){
                        $model->role = \app\models\User::ROLE_ADM;
                    } else {
                        $model->role = \app\models\User::ROLE_USER;
                    }
                    foreach ($roles as $role) {
                        $newRole = $auth->createRole($role);
                        $auth->assign($newRole, $model->id);
                    }
                }

                $model->save(false);
                Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully changed!'));
                if (Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {
                    return $this->refresh();
                } else {
                    //AdmRoot
                    return Adm::redirect(['update', 'id' => $model->id]);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'dynamicModel' => $dynamicModel,
        ]);
    }

    /**
     * @param $item_name
     * @return string
     */
    public static function translateAuthItems($item_name, $params = [])
    {
        $params = ArrayHelper::merge(['dot' => false], $params);
        return Adm::t('sumoselect/items', $item_name, $params);
    }

    /**
     * @param $childs
     * @param $item_name
     * @param $clone
     * @param int $level
     * @return bool
     */
    public function recursiveAuthItems($childs, $item_name, &$clone = [], $level = 0) {

        if ($level == 0) {
            $prefix = '';
            $clone = [];
            $class = '';
        } else {
            $prefix = Html::tag('span', '', ['class' => 'fa fa-long-arrow-right']) . '&nbsp;';
            $class = ' auth-item-childname';
        }

        echo Html::beginTag('div', ['class' => 'auth-item']);
        echo Html::tag('div', $prefix . self::translateAuthItems($item_name, ['dot' => true]), ['class' => 'auth-item-name' . $class]);
        if (isset($childs[$item_name])) {
            echo Html::beginTag('div', ['class' => 'auth-items-childs']);
                if (isset($clone[$item_name])) {
                    echo Html::tag('div', '...', ['class' => 'auth-item-repeat']);
                } else {
                    $clone[$item_name] = 1;
                    foreach ($childs[$item_name] as $child) {
                        if (!in_array($child, $this->excludeRole)) {
                            $this->recursiveAuthItems($childs, $child, $clone ,$level + 1);
                        }

                    }
                }
            echo Html::endTag('div');
        }
        echo Html::endTag('div');

    }
}
