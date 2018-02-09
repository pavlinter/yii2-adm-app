<?php

use app\modules\magnific\widgets\MagnificModal;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $widget \app\modules\cropper\widgets\Cropper */
?>

<div <?= Html::renderTagAttributes($widget->options) ?>>
    <?php MagnificModal::begin($widget->popupConfig); ?>
    <?php $form = ActiveForm::begin($widget->formConfig); ?>
    <div class="avatar-body">
        <div class="avatar-upload">
            <input type="hidden" class="avatar-src" name="avatar_src">
            <input type="hidden" class="avatar-data" name="avatar_data">
            <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
        </div>
        <div class="avatar-message"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="avatar-wrapper"></div>
            </div>
        </div>
        <div class="row avatar-btns">
            <div class="col-xs-12 col-sm-6 col-md-6 text-xs-center">
                <button type="button" class="btn btn-primary" data-cropmethod="rotate" data-option="-90">
                    <span class="fa fa-rotate-left"></span>
                </button>
                <button type="button" class="btn btn-primary" data-cropmethod="rotate" data-option="90">
                    <span class="fa fa-rotate-right"></span>
                </button>
                <button type="button" class="btn btn-primary" data-cropmethod="zoom" data-option="0.1">
                    <span class="fa fa-search-plus"></span>
                </button>
                <button type="button" class="btn btn-primary" data-cropmethod="zoom" data-option="-0.1">
                    <span class="fa fa-search-minus"></span>
                </button>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 mt-xs-10">
                <button type="submit" class="btn btn-primary btn-block avatar-save"><?= Yii::t("app/cropper", "Done") ?></button>
            </div>
        </div>


    </div>
    <?php ActiveForm::end(); ?>
    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
    <?php MagnificModal::end(); ?>
</div>

