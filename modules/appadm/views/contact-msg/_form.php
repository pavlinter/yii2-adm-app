<?php

use yii\helpers\Html;
use pavlinter\buttons\InputButton;
use pavlinter\adm\Adm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ContactMsg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-msg-form">

    <?php $form = Adm::begin('ActiveForm'); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4">
            <?= $form->field($model, 'from_email')->textInput(['maxlength' => 320]) ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <?= $form->field($model, 'to_email')->textInput(['maxlength' => 320]) ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <?= $form->field($model, 'subject')->textInput(['maxlength' => 300]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
        </div>
    </div>



    <div class="form-group">
        <?=  InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create', ['dot' => false]) : Adm::t('', 'Update', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'formSelector' => $form,
        ]);?>

        <?php  if ($model->isNewRecord) {?>
            <?=  InputButton::widget([
                'label' => Adm::t('', 'Create and insert new', ['dot' => false]),
                'options' => ['class' => 'btn btn-primary'],
                'input' => 'adm-redirect',
                'name' => 'redirect',
                'value' => Url::to(['create']),
                'formSelector' => $form,
            ]);?>
        <?php  }?>

        <?=  InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create and list', ['dot' => false]) : Adm::t('', 'Update and list', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'value' => Url::to(['index']),
            'formSelector' => $form,
        ]);?>
    </div>

    <?php Adm::end('ActiveForm'); ?>

</div>
