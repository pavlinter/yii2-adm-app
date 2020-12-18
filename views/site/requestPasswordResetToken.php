<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\form\PasswordResetRequestForm */

$this->title = Yii::t("app/passwordReset", "Request password reset", ['dot' => false]);
$appAsset = \app\assets_b\AppAsset::register($this);
$this->context->layout = '/main';
?>

<div class="site-request-password-reset">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <?= \app\widgets\Alert::widget() ?>

            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
                'options' => [
                    'class' => 'margin-bottom-0',
                ],
            ]); ?>

            <div class="m-b-20 text-center">
                <?= Yii::t("app/passwordReset", "Please fill out your email. A link to reset password will be sent there.") ?>
            </div>

            <?= $form->field($model, 'email', [
                'options' => [
                    'class' => 'form-group m-b-20',
                ],
            ])->textInput([
                'class' => 'form-control input-lg',
                'placeholder' => $model->getAttributeLabel('email'),
            ])->label(false) ?>

            <div class="login-buttons">
                <?= Html::submitButton(Yii::t("app/passwordReset", "Send", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg']) ?>
                <?= Yii::t("app/passwordReset", "Send", ['dot' => '.']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
