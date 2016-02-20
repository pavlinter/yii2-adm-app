<?php
use app\modules\admhidemenu\Module;
use kartik\checkbox\CheckboxX;
use pavlinter\adm\Adm;
use pavlinter\buttons\InputButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\admhidemenu\models\SettingsForm */
$m = Module::getInstance();
$adm = Yii::$app->getModule('adm');
$items = $m->leftMenu;
\app\modules\admhidemenu\StyleAsset::register($this);
?>

<div class="admhidemenu-default-index">
    <?= Module::trasnalateLink() ?>
    <h1><?= Module::t('', 'Root Tools') ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'admhidemenu-form',
    ]);

    ?>

    <div class="flex-cont">

        <?php foreach ($items as $itemName => $item) {?>
            <?php
            if (!isset($model->items[$itemName])) {
                $model->items[$itemName] = 1;
            }
            ?>

            <div class="flex-item">
                <?= $form->field($model, 'items[' . $itemName . ']', ['template' => '{input}{label}'])->label($item['label'])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]); ?>
                <?php if (isset($item['items'])) {?>

                    <?php foreach ($item['items'] as $child) {?>
                        <?php if (isset($child['key'])) {?>
                            <div style="padding-left: 30px;">
                                <?php
                                if (!isset($model->items[$child['key']])) {
                                    $model->items[$child['key']] = 1;
                                }
                                ?>
                                <?= $form->field($model, 'items[' . $child['key'] . ']', ['template' => '{input}{label}'])->label($child['label'])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]); ?>
                            </div>
                        <?php }?>
                    <?php }?>
                <?php }?>
            </div>
        <?php }?>
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
