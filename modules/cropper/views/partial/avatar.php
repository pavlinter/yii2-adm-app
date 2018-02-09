<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $widget \app\modules\cropper\widgets\Cropper */

?>

<div <?= Html::renderTagAttributes($widget->options) ?>>
    <?= Html::beginTag('div', $widget->avatarViewOptions) ?>
    <div <?= Html::renderTagAttributes($widget->options) ?>>
        <?= Html::img($widget->image, $widget->imageOptions) ?>
    </div>
    <?= Html::endTag('div') ?>
</div>


