<?php
use app\modules\admlivechat\Module;
use kartik\checkbox\CheckboxX;
use pavlinter\adm\Adm;
use pavlinter\buttons\InputButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\admlivechat\models\SettingsForm */
?>

<div class="admtinypng-default-index">
    <?= Module::trasnalateLink() ?>
    <h1><?= Module::t('', 'Live Chat') ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'admlivechat-form',
    ]);
    ?>

        <section class="panel adm-langs-panel">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified text-uc">
                    <?php  foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
                        <li><a href="#lang-<?= $id_language ?>" data-toggle="tab"><?= $language['name'] ?></a></li>
                    <?php  }?>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <?php
                    foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) {
                        ?>
                        <div class="tab-pane" id="lang-<?= $id_language ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?= $form->field($model, 'scripts['.$id_language.']')->textarea(['rows' => 12,'value' => (isset($model->scripts[$id_language]) ? $model->scripts[$id_language] : '')]) ?>
                                </div>
                            </div>
                        </div>
                    <?php  }?>
                </div>
            </div>
        </section>

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
