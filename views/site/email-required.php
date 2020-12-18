<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\form\PasswordResetRequestForm */
/* @var $showOnlyAlert boolean */


$this->title = Yii::t("app/emailRequired", "Email Required", ['dot' => false]);
$appAsset = \app\assets_b\AppAsset::register($this);
$this->context->layout = '/main';
?>

<div class="site-request-password-reset">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <?= \app\widgets\Alert::widget() ?>

            <?php if (!$showOnlyAlert) {?>
                <?php $form = ActiveForm::begin([
                    'id' => 'email-required-form',
                    'options' => [
                        'class' => 'mb-0',
                    ],



                ]); ?>

                <div class="m-b-20 text-center">
                    <?= Yii::t("app/emailRequired", "Please fill out your email. A link to reset password will be sent there.") ?>
                </div>

                <?= $form->field($model, 'email', [
                'errorOptions' => [
                    'encode' => false,
                ],
                'options' => [
                    'class' => 'form-group m-b-20',
                ],
            ])->textInput([
                'class' => 'form-control input-lg',
                'placeholder' => $model->getAttributeLabel('email'),
            ])->label(false) ?>

                <div class="login-buttons">
                    <?= Html::submitButton(Yii::t("app/emailRequired", "Send", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg']) ?>
                    <?= Yii::t("app/emailRequired", "Send", ['dot' => '.']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            <?php }?>
        </div>
    </div>
</div>
