<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

$this->title = Yii::t("app/passwordReset", "Reset password", ['dot' => false]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t("app/passwordReset", "Please choose your new password:") ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t("app/passwordReset", "Save", ['dot' => false]), ['class' => 'btn btn-primary']) ?>
                    <?= Yii::t("app/passwordReset", "Save", ['dot' => '.']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
