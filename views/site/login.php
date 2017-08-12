<?php
use app\helpers\Url;
use app\modules\icheck\widgets\Checkbox;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\form\LoginForm */

$this->title = Yii::t("app/login", "Log in", ['dot' => false]);

$appAsset = \app\assets_b\AppAsset::register($this);
$this->context->layout = '/main';
?>
<div class="site-login">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <?= \app\widgets\Alert::widget(); ?>

            <?php $authAuthChoice = \app\widgets\AuthChoice::begin([
                'baseAuthUrl' => ['/site/auth']
            ]); ?>
            <div class="m-b-20">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?= $authAuthChoice->clientLink($client, '<i class="fa fa-facebook pull-left"></i><span>' . Yii::t("app", "Connect With Facebook") . '</span>', ['class' => 'btn btn-facebook']) ?>
                <?php endforeach; ?>
            </div>
            <?php \app\widgets\AuthChoice::end(); ?>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'margin-bottom-0'],
                'fieldConfig' => [
                    'template' => "{input}{error}",
                ],
            ]); ?>
            <?= $form->field($model, 'username', [
                'options' => [
                    'class' => 'form-group m-b-15',
                ],
            ])->textInput([
                'class' => 'form-control input-lg',
                'placeholder' => $model->getAttributeLabel('username'),
            ]); ?>

            <?= $form->field($model, 'password', [
                'options' => [
                    'class' => 'form-group m-b-15',
                ],
            ])->passwordInput([
                'class' => 'form-control input-lg',
                'placeholder' => $model->getAttributeLabel('password'),
            ]); ?>

            <?= $form->field($model, 'rememberMe')->widget(Checkbox::className(), [
                'options' => [
                    'class' => 'm-r-10',
                ],
                'textOptions' => [
                    'class' => 'p-l-10 inline-block',
                ],
                'containerOptions' => [
                    'class' => 'm-b-30',
                ],
                'skin' => Checkbox::SKIN_MINIMAL_ORANGE,
            ]) ?>



            <div class="login-buttons">
                <?= Html::submitButton(Yii::t("app/login", "Login", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>
                <?= Yii::t("app/login", "Login", ['dot' => '.']) ?>
            </div>

            <div class="form-group m-t-30 pull-left">
                <a href="<?= Url::to(['/site/signup']) ?>"><?= Yii::t("app/login", "Register link", ['dot' => false]) ?></a>
            </div>
            <div class="form-group m-t-30 pull-right">
                <a href="<?= Url::to(['/site/request-password-reset']) ?>"><?= Yii::t("app/login", "Forgot link", ['dot' => false]) ?></a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>