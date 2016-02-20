<?php
use app\modules\admgoogletools\Module;
use kartik\checkbox\CheckboxX;
use pavlinter\adm\Adm;
use pavlinter\buttons\InputButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\admgoogletools\models\SettingsForm */
?>

<div class="admtinypng-default-index">
    <?= Module::trasnalateLink() ?>
    <h1><?= Module::t('', 'Google Analytic') ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'admgoogletools-form',
    ]);
    ?>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div>
                    <?= $form->field($model, 'analytic')->textarea(); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div>
                    <?= $form->field($model, 'webtools')->textarea(); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div>
                    <?= $form->field($model, 'active', ["template" => "{input}\n{label}\n{hint}\n{error}"])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]); ?>
                </div>
            </div>
        </div>

        <p>
            <?= InputButton::widget([
                'label' => !isset(Yii::$app->params[Module::getInstance()->settingsKey]) ? Adm::t('', 'Create', ['dot' => false]) : Adm::t('', 'Update', ['dot' => false]),
                'options' => ['class' => 'btn btn-primary'],
                'input' => 'adm-redirect',
                'name' => 'redirect',
                'formSelector' => $form,
            ]);?>

        </p>
    <?php ActiveForm::end(); ?>
</div>
