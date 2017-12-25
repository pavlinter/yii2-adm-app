<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
$tableSchema = $generator->getTableSchema();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use pavlinter\adm\Adm;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \app\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['AdmRoot', 'AdmAdmin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \app\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param integer $id
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionFiles($id<?= $generator->ifParent(', $id_parent = false') ?>)
    {
        /*
        '<?= strtolower($modelClass) ?>' => [
            'imagesWebDir' => '@web/files/data/<?= strtolower($modelClass) ?>',
            'imagesDir' => '@webroot/files/data/<?= strtolower($modelClass) ?>',
            'defaultWebDir' => '@web/files/default',
            'defaultDir' => '@webroot/files/default',
            'mode' => \pavlinter\display2\objects\Image::MODE_OUTBOUND,
        ],
        */

        $model = $this->findModel($id);

        $dirs = ['main'];
        foreach ($dirs as $dir) {
            \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot/files/data/<?= strtolower($modelClass) ?>/' . $id . '/' . $dir));
        }
        $startPathDir = '';
        if(count($dirs) == 1){
            $startPathDir = '::' . $dirs['0'];
        }
        $startPath = 'data::<?= strtolower($modelClass) ?>::' . $id . $startPathDir;

        $elfinderData = [];
        $elfinderData['w'] = Yii::$app->params['elfinder.maxWidth'];
        $elfinderData['h'] = Yii::$app->params['elfinder.maxHeight'];
        //$elfinderData['watermark'] = 1;

        return $this->render('files', [
            'model' => $model,
            'startPath' => $startPath,
            'elfinderData' => $elfinderData,
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
    }

    /**
     * Lists all <?= $modelClass ?> models.
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionIndex(<?= $generator->ifParent('$id_parent = false') ?>)
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams<?= $generator->ifParent(', $id_parent') ?>);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?><?= $generator->ifParent(', $id_parent = false') ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionCreate(<?= $generator->ifParent('$id_parent = false') ?>)
    {
        $model = new <?= $modelClass ?>();
        $model->loadDefaultValues();
<?php if ($generator->enableLanguage) {?>
        if ($model->loadAll(Yii::$app->request->post()) && $model->validateAll()) {
            if ($model->save(false) && $model->saveTranslations(false)) {
                Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully inserted!'));
                return Adm::redirect(['update', <?= $urlParams ?><?= $generator->ifParent(', \'id_parent\' => $id_parent') ?>]);
            }
        }  else {
            $copy_id = Yii::$app->request->get('copy_id');
            if($copy_id){
                $model = $this->findModel($copy_id);
                $model->setIsNewRecord(true);
<?php if ($generator->checkCol('weight', ['comment' => 'weight'])) {?>
                $model->weight = null;
<?php }?>
            }<?= $generator->ifParent(" elseif(\$id_parent !== false) {\n")?>
<?php if ($generator->getParentColumn()) {?>
                $model-><?= $generator->getParentColumn() ?> = $id_parent;
            }
<?php }?>
        }
<?php } else {?>
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully inserted!'));
            return Adm::redirect(['update', <?= $urlParams ?><?= $generator->ifParent(', \'id_parent\' => $id_parent') ?>]);
        }  else {
            $copy_id = Yii::$app->request->get('copy_id');
            if($copy_id){
                $model = $this->findModel($copy_id);
                $model->setIsNewRecord(true);
<?php if ($generator->checkCol('weight', ['comment' => 'weight'])) {?>
                $model->weight = null;
<?php }?>
            }<?= $generator->ifParent(" elseif(\$id_parent !== false) {\n")?>
<?php if ($generator->getParentColumn()) {?>
                $model-><?= $generator->getParentColumn() ?> = $id_parent;
            }
<?php }?>
        }
<?php }?>
        return $this->render('create', [
            'model' => $model,
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?><?= $generator->ifParent(', $id_parent = false') ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
<?php if ($generator->enableLanguage) {?>
        if ($model->loadAll(Yii::$app->request->post()) && $model->validateAll()) {
            if ($model->save(false) && $model->saveTranslations(false)) {
                Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully changed!'));
                return Adm::redirect(['update', <?= $urlParams ?><?= $generator->ifParent(', \'id_parent\' => $id_parent') ?>]);
            }
        }
<?php } else {?>
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully changed!'));
            return Adm::redirect(['update', <?= $urlParams ?><?= $generator->ifParent(', \'id_parent\' => $id_parent') ?>]);
        }
<?php }?>
        return $this->render('update', [
            'model' => $model,
<?php if ($generator->getParentColumn()) {?>
            'id_parent' => $id_parent,
<?php }?>
        ]);
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
<?php if ($generator->getParentColumn()) {?>
     * @param boolean|integer $id_parent
<?php }?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?><?= $generator->ifParent(', $id_parent = false') ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();
        Yii::$app->getSession()->setFlash('success', Adm::t('','Data successfully removed!'));
        $url = ['index', 'id' => null];
<?php if ($generator->getParentColumn()) {?>
        if ($id_parent !== false) {
            $url['id_parent'] = $id_parent;
        }
<?php }?>
        return Adm::redirect(\app\helpers\Url::current($url));
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
<?php if ($generator->enableLanguage && count($pks) === 1) {?>
        if (($model = <?= $modelClass ?>::find()->with(['translations'])->where(['id' => <?= $condition ?>])->one()) !== null) {
<?php } else {?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
<?php }?>
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
