<?php
use app\widgets\Alert;
use app\widgets\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \app\models\form\ContactForm */
/* @var $modelPage \app\core\admpages\models\Page */

$this->title = $modelPage->title;
Yii::$app->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-page">
    <h1><?= $this->title ?></h1>

    <?= $modelPage->text ?>

    <?php $form = ActiveForm::begin([
        'id' => 'contactForm',
        'options' => [
            'class' => 'form contactForm',
        ],
    ]);
    echo Yii::t("app/contacts", "Thank you for contacting us. We will respond to you as soon as possible.", ['dot' => '.']);
    echo Yii::t("app/contacts", "There was an error sending email.", ['dot' => '.']);
    ?>



    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">

            <?= Alert::widget() ?>

            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'phone')->textInput() ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'body')->textArea() ?>


            <?php if (IS_LOCALHOST) {?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'captchaAction' => '/site/captcha',
                    'template' => '<div class="row"><div class="col-md-6">{input}</div><div class="col-md-6 mt-mob-10">{image}</div></div> '
                ]) ?>
            <?php } else { ?>

                <?= $form->field($model, 'verifyCode')->label('')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::class) ?>

            <?php }?>


            <p>
                <?= Html::submitInput(Yii::t("app/contacts", "Send", ['dot' => false]), ['class' => 'btn btn-inverse btn-lg']) ?>
                <?= Yii::t("app/contacts", "Send", ['dot' => '.']) ?>
            </p>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
</div>
