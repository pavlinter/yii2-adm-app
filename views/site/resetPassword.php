<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\form\ResetPasswordForm */

$this->title = Yii::t("app/passwordReset", "Reset password", ['dot' => false]);
$appAsset = \app\assets_b\AppAsset::register($this);
$this->context->layout = '/main';
?>
<div class="site-reset-password">


    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <?= \app\widgets\Alert::widget() ?>

            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
                'options' => [
                    'class' => 'margin-bottom-0',
                ],
            ]); ?>

            <div class="m-b-20 text-center">

                <?= Yii::t("app/passwordReset", "Please choose your new password:") ?>

            </div>




            <?= $form->field($model, 'password', [
                'options' => [
                    'class' => 'form-group m-b-20',
                ],
            ])->passwordInput([
                'class' => 'form-control input-lg',
                'placeholder' => $model->getAttributeLabel('password'),
            ])->label(false) ?>

            <div class="login-buttons">
                <?= Html::submitButton(Yii::t("app/passwordReset", "Save", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg']) ?>
                <?= Yii::t("app/passwordReset", "Save", ['dot' => '.']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
