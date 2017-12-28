<?php

namespace app\core\adm\controllers;




use app\filters\VerbFilter;
use app\modules\cloud\Cloud;
use Imagine\Image\Box;
use Imagine\Image\Point;
use pavlinter\adm\filters\AccessControl;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;
use pavlinter\adm\Adm;
use Yii;
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
    public $spaceName = 'user-avatar';

    public $excludeRole = [
        'Adm-UpdateOwnUser',
        'Adm-Transl',
        'Adm-Transl:Html',
    ];

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
                    [
                        'allow' => true,
                        'roles' => ['Adm-User'],
                        'actions' => ['update', 'remove-avatar', 'avatar'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'remove-avatar' => ['post'],
                ],
            ],
        ];
    }

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
        $dynamicModel->clearErrors();

        $post = Yii::$app->request->post();
        if ($model->load($post) && $dynamicModel->load($post)) {
            if ($model->validate() && $dynamicModel->validate()) {
                $model->setPassword($dynamicModel->password);

                if ($model->save(false)) {
                    if (!Adm::getInstance()->user->can('Adm-User', $model)) {
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
                    $this->saveAvatar($model);
                    Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully inserted!'));
                    return Adm::redirect(['update', 'id' => $model->id]);
                }
            }
        }

        $authItems = Adm::getInstance()->manager->createAuthItemQuery('find')->select(['name'])->where(['type' => Item::TYPE_ROLE])->all();
        $this->clearSpaceAvatar();
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

        if (Adm::getInstance()->user->can('Adm-User', $model)) {
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

                if (!Adm::getInstance()->user->can('Adm-User', $model)) {
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
                $this->saveAvatar($model);
                if (Adm::getInstance()->user->can('Adm-User', $model)) {
                    return $this->refresh();
                } else {
                    //AdmRoot
                    return Adm::redirect(['update', 'id' => $model->id]);
                }
            }
        }

        $this->clearSpaceAvatar();

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

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionRemoveAvatar($id)
    {
        $model = $this->findModel($id);
        if (Adm::getInstance()->user->can('AdmRoot') || Adm::getInstance()->user->can('Adm-User', $model)) {
            $path = Yii::getAlias('@webroot/files/data/user/' . $model->id . '/main');
            FileHelper::removeDirectory($path);
        } else {
            throw new ForbiddenHttpException('Access denied');
        }
        return Adm::goBack(['update', 'id' => $model->id]);
    }


    /**
     * @return string
     */
    public function actionAvatar()
    {
        /* @var $storage \app\modules\cloud\components\Storage */
        $storage = Cloud::getInst()->storage;
        $spaceName = $this->spaceName;
        $path = $storage->getPath($spaceName);

        $minSize = 600;
        $avatar_src = Yii::$app->request->post('avatar_src');
        $avatar_data = Yii::$app->request->post('avatar_data', '{}');
        $data = Json::decode($avatar_data);
        $path = FileHelper::normalizePath($path) . DIRECTORY_SEPARATOR;
        $file = UploadedFile::getInstanceByName('avatar_file');

        $model = new DynamicModel(compact('file'));
        $model->addRule('file', 'image', [
            'extensions' => 'gif, jpg, png',
            'maxSize' => 1024 * 1024 * 5,
            'minWidth' => $minSize,
            'minHeight' => $minSize,
        ])->validate();
        /* @var $file \yii\web\UploadedFile */
        $file = $model->file;
        //$json['message'] = '';
        $json['result'] = '';

        if ($model->hasErrors()) {
            $json['message'] = $model->getFirstError('file');
        } else {
            if ($file->extension) {
                $file->name = uniqid() . '.' . $file->extension;
            }
            FileHelper::removeDirectory($path);
            if (!FileHelper::createDirectory($path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }

            $originalImage = Image::getImagine()->open($file->tempName);
            $box = new Box(round($data['width']), round($data['height']));

            if ($box->getWidth() < $minSize || $box->getHeight() < $minSize) {
                $json['message'] = Yii::t('app/cropper', 'Too much zoom!');
            } else {
                $newImage = $originalImage->crop(new Point($data['x'], $data['y']), $box)
                    ->thumbnail(new Box($minSize, $minSize));

                $r = $newImage->save($path . $file->name);
                if ($r) {
                    $images = $storage->getWebCloudFiles();
                    if ($images) {
                        $json['result'] = $images['0'];
                    }
                } else {
                    $json['message'] = Yii::t('app', 'Oops, something went wrong. Please try again!');
                }
            }
        }

        $json['state'] = 200;
        return Json::encode($json);
    }

    /**
     * @param $model
     */
    protected function saveAvatar($model){
        /* @var $storage \app\modules\cloud\components\Storage */
        $spaceName = $this->spaceName;
        $storage = Cloud::getInst()->storage;
        if ($storage->hasName($spaceName)) {
            $path = Yii::getAlias('@webroot/files/data/user/' . $model->id . '/main');
            FileHelper::removeDirectory($path);
            $storage->moveFileAndClear($path, $spaceName);
        }
        $storage->removeOldDir(60 * 60 * 24 * 1); //1 day
    }

    /**
     * @param $model
     */
    protected function clearSpaceAvatar()
    {
        /* @var $storage \app\modules\cloud\components\Storage */
        $spaceName = $this->spaceName;
        $storage = Cloud::getInst()->storage;
        $storage->removeCloudDir($spaceName);
        $storage->clear($spaceName);
    }
}
