<?php
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = Yii::t("app/signup", "Registration", ['dot' => false]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t("app/signup", "Please fill out the following fields to signup:") ?></p>

    <?= \app\widgets\Alert::widget() ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className()) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t("app/signup", "Signup", ['dot' => false]), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Yii::t("app/signup", "Signup", ['dot' => '.']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
