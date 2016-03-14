<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
echo "<?php\n";
?>

use pavlinter\buttons\InputButton;
use pavlinter\adm\Adm;
use app\helpers\Url;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
<?php if ($generator->getParentColumn()) {?>
/* @var $id_parent boolean|integer */

$childQuery = \<?= ltrim($generator->modelClass, '\\') ?>::find()->with(['childs'])->where(['<?= $generator->getParentColumn() ?>' => null]);
if (!$model->isNewRecord) {
    $childQuery->andWhere(['!=', 'id', $model->id])->all();
}
$childModels = $childQuery->all();

$childsData = [];

\app\helpers\TreeHelper::treeMap($childsData, $childModels, 'id' , '<?= $generator->getNameAttribute() ?>', [
    'levelOptions' => [
        '0' => ['style' => 'font-weight: bold;'],
        '1' => ['style' => 'color: #ffc321;'],
        '2' => ['style' => 'color: #ff927c;'],
        'else' => ['style' => 'color: #aadd8a;'],
    ],
]);


<?php }?>
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = Adm::begin('ActiveForm'); ?>
<?php if ($generator->enableLanguage) { ?>

    <?= "<?=" ?> $form->errorSummary([$model] + $model->getLangModels(), ['class' => 'alert alert-danger']); ?>
<?php }?>
<?php if ($generator->getParentColumn()) {?>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <?= "<?=" ?> $form->field($model, '<?= $generator->getParentColumn() ?>')->widget(\kartik\widgets\Select2::classname(), [
            'data' => $childsData,
            'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
            'pluginOptions' => [
                'allowClear' => true,
                'escapeMarkup' => new \yii\web\JsExpression('function (m) { return m; }'),
            ]
            ]); ?>
        </div>
    </div>
<?php }?>

    <?php
    $columnNames = $generator->getColumnNames();
    if ($columnNames) {?>
<div class="row">
        <?php foreach ($columnNames as $i => $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                if($generator->getParentColumn() == $attribute){
                    continue;
                }
                echo $generator->generateActiveField($attribute, ['fix' => true]);
            }
        } ?></div>
    <?php }?>

<?php if ($generator->enableLanguage) {
    $modelClass = new $generator->modelClass();

    $behaviors = $modelClass->behaviors();

    if (isset($behaviors['trans'],$behaviors['trans']['translationAttributes'])) {
?>
<?php if ($generator->languagePanelType === 'panelTab') { ?>
    <section class="panel adm-langs-panel">
        <header class="panel-heading bg-light">
            <ul class="nav nav-tabs nav-justified text-uc">
            <?= "<?php " ?> foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
                <li><a href="#lang-<?= "<?= " ?> $id_language ?>" data-toggle="tab"><?= "<?= " ?> $language['name'] ?></a></li>
            <?= "<?php " ?> }?>
            </ul>
        </header>
        <div class="panel-body">
            <div class="tab-content">
                <?= "<?php " ?> foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
                    <div class="tab-pane" id="lang-<?= "<?= " ?> $id_language ?>">
                        <div class="row">
<?php foreach ($behaviors['trans']['translationAttributes'] as $translateField) {?>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <?= "<?= " . $generator->generateActiveFieldLang($translateField) . " ?>"; ?>

                            </div>
<?php }?>
                        </div>
                    </div>
                <?= "<?php " ?> }?>
            </div>
        </div>
    </section>
<?php } else {?>
    <?= "<?php " ?> foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
        <section class="panel pos-rlt clearfix adm-langs-panel-toggle">
            <header class="panel-heading">
                <ul class="nav nav-pills pull-right">
                    <li>
                        <a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a>
                    </li>
                </ul>
                <h3 class="panel-title"><?= "<?=" ?> $language['name'] ?></h3>
            </header>
            <div class="panel-body clearfix">
                <div class="row">
<?php foreach ($behaviors['trans']['translationAttributes'] as $translateField) {?>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <?= "<?= " . $generator->generateActiveFieldLang($translateField) . " ?>"; ?>

                        </div>
<?php }?>
                </div>
            </div>
        </section>
    <?= "<?php " ?> }?>
    <?php }?>

<?php }}?>

    <div class="form-group">
        <?= "<?= " ?> InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create', ['dot' => false]) : Adm::t('', 'Update', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'formSelector' => $form,
        ]);?>

        <?= "<?php " ?> if ($model->isNewRecord) {?>
            <?= "<?= " ?> InputButton::widget([
                'label' => Adm::t('', 'Create and insert new', ['dot' => false]),
                'options' => ['class' => 'btn btn-primary'],
                'input' => 'adm-redirect',
                'name' => 'redirect',
                'value' => Url::current(),
                'formSelector' => $form,
            ]);?>
        <?= "<?php " ?> }?>

        <?= "<?= " ?> InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create and list', ['dot' => false]) : Adm::t('', 'Update and list', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'value' => Url::current(['index']),
            'formSelector' => $form,
        ]);?>

        <?= "<?= " ?> InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create and files', ['dot' => false]) : Adm::t('', 'Update and files', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'value' =>  Url::current(['files', 'id' => '{id}']),
            'formSelector' => $form,
        ]);?>
    </div>

    <?= "<?php " ?>Adm::end('ActiveForm'); ?>

</div>
