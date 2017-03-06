<?php
use app\modules\admevents\Module;
use kartik\checkbox\CheckboxX;
use pavlinter\adm\Adm;
use pavlinter\buttons\InputButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\admevents\models\SettingsForm */
?>

<div class="admevents-default-index">
    <?= Module::trasnalateLink() ?>
    <h1><?= Module::t('', 'Events') ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'admevents-form',
    ]);
    ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?= $form->field($model, 'head')->textarea(['rows' => 10]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <?= $form->field($model, 'beginBody')->textarea(['rows' => 10]); ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <?= $form->field($model, 'endBody')->textarea(['rows' => 10]); ?>
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
