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
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'id' => '',
        'options' => [
            //'enctype'=>'multipart/form-data',
            'class' => '',
        ],
        'fieldConfig' => [],
    ]); ?>


    <?= "<?=" ?> $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <?php
    $columnNames = $generator->getColumnNames();
    if ($columnNames) {?>
<div class="row">
        <?php foreach ($columnNames as $i => $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                if(in_array($attribute, [$generator->getParentColumn(), 'active', 'weight'])){
                    continue;
                }
                echo $generator->generateActiveField($attribute, ['fix' => true]);
            }
        } ?></div>
    <?php }?>


    <div class="row">
<?php if (in_array('weight', $columnNames)) {?>
<?= "\t\t" . $generator->generateActiveField('weight', ['fix' => true]); ?>
<?php }?><?php if (in_array('active', $columnNames)) {?>
<?= $generator->generateActiveField('active', ['fix' => true, 'isFrontend' => true]); ?>
<?php }?>
</div>


    <div class="form-group">
        <?= "<?= " ?> InputButton::widget([
            'label' => $model->isNewRecord ? Yii::t('app', 'Create', ['dot' => false]) : Yii::t('app', 'Update', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'formSelector' => $form,
        ]);?>

    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
