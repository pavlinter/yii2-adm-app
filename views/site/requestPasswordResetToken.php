<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

$this->title = Yii::t("app/passwordReset", "Request password reset", ['dot' => false]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t("app/passwordReset", "Please fill out your email. A link to reset password will be sent there.") ?></p>

    <?= \app\widgets\Alert::widget() ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t("app/passwordReset", "Send", ['dot' => false]), ['class' => 'btn btn-primary']) ?>
                    <?= Yii::t("app/passwordReset", "Send", ['dot' => '.']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
