<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admgii\components\ActiveField;
use app\modules\admgii\CodeFile;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\Generator */
/* @var $id string panel ID */
/* @var $form yii\widgets\ActiveForm */
/* @var $results string */
/* @var $hasError boolean */
/* @var $files CodeFile[] */
/* @var $answers array */

$this->title = $generator->getName();
?>
<div class="default-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $generator->getDescription() ?></p>

    <?php $form = ActiveForm::begin([
        'id' => "$id-generator",
        'successCssClass' => '',
        'fieldConfig' => ['class' => ActiveField::className()],
    ]); ?>

        <?php
        if (isset($results)) {
            echo $this->render('view/results', [
                'generator' => $generator,
                'results' => $results,
                'hasError' => $hasError,
            ]);
        }
        ?>

        <div class="row">
            <div class="col-lg-8 col-md-10">
                <?= $this->renderFile($generator->formView(), [
                    'generator' => $generator,
                    'form' => $form,
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Create', ['name' => 'preview', 'class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
