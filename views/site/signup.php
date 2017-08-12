<?php
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\form\SignupForm */

$this->title = Yii::t("app/signup", "Registration", ['dot' => false]);
$appAsset = \app\assets_b\AppAsset::register($this);
$this->context->layout = '/main';
?>
<div class="site-signup">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <?= \app\widgets\Alert::widget() ?>

            <?php $authAuthChoice = \app\widgets\AuthChoice::begin([
                'baseAuthUrl' => ['/site/auth']
            ]); ?>
            <div class="mb-20">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?= $authAuthChoice->clientLink($client, '<i class="fa fa-facebook pull-left"></i><span>' . Yii::t("app", "Connect With Facebook") . '</span>', ['class' => 'btn btn-facebook']) ?>
                <?php endforeach; ?>
            </div>
            <?php \app\widgets\AuthChoice::end(); ?>


            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'options' => ['class' => 'margin-bottom-0'],
                'fieldConfig' => [
                    'options' => ['class' => 'm-b-10'],
                    'template' => '{label}<div class="row"><div class="col-md-12">{input}</div></div>{error}',
                ],
            ]); ?>



            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>


            <?php if (IS_LOCALHOST) {?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className()) ?>
            <?php } else { ?>
                <?= $form->field($model, 'verifyCode')->label('')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
            <?php }?>


            <div class="register-buttons mt-30">
                <?= Html::submitButton(Yii::t("app/signup", "Signup", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'signup-button']) ?>
                <?= Yii::t("app/signup", "Signup", ['dot' => '.']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
