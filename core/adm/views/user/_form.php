<?php

use kartik\widgets\Select2;
use pavlinter\buttons\InputButton;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model pavlinter\adm\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $dynamicModel yii\base\DynamicModel */
/* @var $authItems array */

\app\assets_b\SumoselectAsset::register($this);

$authItems = Adm::getInstance()->manager->createAuthItemQuery('find')->where(['not in', 'name', $this->context->excludeRole])->orderBy(['created_at' => SORT_ASC])->asArray()->all();
$authAssignments = Adm::getInstance()->manager->createAuthAssignmentQuery('find')->select("item_name")->where(['user_id' => $model->id])->indexBy('item_name')->asArray()->all();

$this->registerJs('
    $(".sumoselect-role").SumoSelect({
        placeholder: "' . Adm::t('sumoselect', 'Select ...', ['dot' => false]) . '",
        captionFormat:"' . Adm::t('sumoselect', '{0} Selected', ['dot' => false]) . '",
        selectAlltext: "' . Adm::t('sumoselect', 'Select All', ['dot' => false]) . '"
    });
');


$auth = Yii::$app->authManager;

$authItemsArr = Adm::getInstance()->manager->createAuthItemQuery('find')->select(['name' , 'type'])->where(['not in', 'name', $this->context->excludeRole])->orderBy(['created_at' => SORT_ASC])->asArray()->indexBy("name")->all();
$authItemsChildArr = Adm::getInstance()->manager->createAuthItemChildQuery('find')->asArray()->all();
$authItemsChilds = [];
foreach ($authItemsChildArr as $authItemsChild) {
    $authItemsChilds[$authItemsChild['parent']][] = $authItemsChild['child'];
}
?>

<div class="user-form">

    <?php $form = Adm::begin('ActiveForm'); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>


        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?= $form->field($dynamicModel, 'password')->passwordInput()->label(Yii::t('modelAdm/user', 'Password')) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?= $form->field($dynamicModel, 'password2')->passwordInput()->label(Yii::t('modelAdm/user', 'Confirm Password')) ?>
        </div>
    </div>



    <?php if (!Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {?>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <a href="#roles-modal" data-toggle="modal"><span class="glyphicon glyphicon-eye-open"></span></a>
                    <label for="sumoselect-role" class="control-label"><?= Yii::t('modelAdm/user', 'Assignment Role') ?></label>
                    <select class="sumoselect-role SelectClass" name="roles[]" id="sumoselect-role" multiple="multiple">
                        <option class="group-head" disabled><?= Adm::t('sumoselect', 'Roles', ['dot' => false]) ?></option>
                        <?php foreach ($authItems as $authItem) {?>
                            <?php if ($authItem['type'] == 1) {?>
                                <option class="group-item" <?= isset($authAssignments[$authItem['name']]) ? 'selected' : ''; ?> value="<?= $authItem['name'] ?>"><?= $this->context->translateAuthItems($authItem['name']) ?></option>
                            <?php }?>
                        <?php }?>
                        <option class="group-head" disabled><?= Adm::t('sumoselect', 'Permissions', ['dot' => false]) ?></option>
                        <?php foreach ($authItems as $authItem) {?>
                            <?php if ($authItem['type'] == 2) {?>
                                <option class="group-item" <?= isset($authAssignments[$authItem['name']]) ? 'selected' : ''; ?> value="<?= $authItem['name'] ?>"><?= $this->context->translateAuthItems($authItem['name']) ?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                    <?= Adm::t('sumoselect', 'Roles', ['dot' => '.']) ?>
                    <?= Adm::t('sumoselect', 'Permissions', ['dot' => '.']) ?>
                </div>

                <?php
                    Modal::begin([
                        'id' => 'roles-modal',
                        'header' => Html::tag('div', Adm::t('user', 'Tree Roles'), ['class' => 'roles-map']),
                        'size' => Modal::SIZE_LARGE,
                    ]);
                        echo Html::beginTag('div', ['class' => 'auth-items-tree']);
                        echo Html::tag('div', Adm::t('sumoselect', 'Roles'), ['class' => 'auth-items-type']);
                        foreach ($authItemsArr as $name => $item) {
                            if ($item['type'] == 1) {
                                $this->context->recursiveAuthItems($authItemsChilds, $name);
                            }
                        }

                        echo Html::tag('div', Adm::t('sumoselect', 'Permissions'), ['class' => 'auth-items-type']);
                        foreach ($authItemsArr as $name => $item) {
                            if ($item['type'] == 2) {
                                $this->context->recursiveAuthItems($authItemsChilds, $name);
                            }
                        }

                        echo Html::endTag('div');
                    Modal::end();
                ?>

            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <?php
                echo $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => $model::status(),
                    'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
                    'pluginOptions' => [

                    ],
                ]);
                ?>
            </div>
        </div>

    <?php }?>

    <div class="form-group">

        <?= InputButton::widget([
            'label' => $model->isNewRecord ? Adm::t('', 'Create', ['dot' => false]) : Adm::t('', 'Update', ['dot' => false]),
            'options' => ['class' => 'btn btn-primary'],
            'input' => 'adm-redirect',
            'name' => 'redirect',
            'formSelector' => $form,
        ]);?>

        <?php if (!Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {?>

            <?php if ($model->isNewRecord) {?>
                <?= InputButton::widget([
                    'label' => Adm::t('', 'Create and insert new', ['dot' => false]),
                    'options' => ['class' => 'btn btn-primary'],
                    'input' => 'adm-redirect',
                    'name' => 'redirect',
                    'value' => Url::to(['create']),
                    'formSelector' => $form, //form object or form selector
                ]);?>
            <?php }?>


            <?= InputButton::widget([
                'label' => $model->isNewRecord ? Adm::t('', 'Create and list', ['dot' => false]) : Adm::t('', 'Update and list', ['dot' => false]),
                'options' => ['class' => 'btn btn-primary'],
                'input' => 'adm-redirect',
                'name' => 'redirect',
                'value' => Url::to(['index']),
                'formSelector' => $form, //form object or form selector
            ]);?>

            <?= InputButton::widget([
                'label' => $model->isNewRecord ? Adm::t('', 'Create and viewing', ['dot' => false]) : Adm::t('', 'Update and viewing', ['dot' => false]),
                'options' => ['class' => 'btn btn-primary'],
                'input' => 'adm-redirect',
                'name' => 'redirect',
                'value' => Url::to(['view', 'id' => '{id}']),
                'formSelector' => $form, //form object or form selector
            ]);?>

        <?php }?>
    </div>

    <?php Adm::end('ActiveForm'); ?>

</div>
