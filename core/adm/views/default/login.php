<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\core\adm\models\LoginForm $model
 */

?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a href="<?= Url::base(true) ?>" class="nav-brand" target="_blank">
        Adm
        <sup>cms</sup>
    </a>
    <div class="row m-n">
        <div class="col-md-4 col-md-offset-4 m-t-lg">
            <section class="panel">
                <?php $form = ActiveForm::begin(['id' => 'login-form','options' => ['class' => 'panel-body']]); ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <?= Html::submitButton(Yii::t("adm/login", "Login", ['dot' => false]), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <div class="line line-dashed"></div>

                    <div class="pull-right">
                        <a href="<?= Url::to(['/site/request-password-reset']) ?>"><?= Yii::t("adm/login", "Forgot link", ['dot' => false]) ?></a>
                    </div>

                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</section>
