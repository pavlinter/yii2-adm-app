<?php

use app\assets_b\AppAsset;
use pavlinter\admpages\Module;

/* @var $this yii\web\View */
/* @var $model \pavlinter\admpages\models\Page */

Module::getInstance()->layout = Module::getInstance()->pageLayout;
$this->title = $model->title;
$appAsset = AppAsset::register($this);
Yii::$app->params['html.canonical'] = Yii::$app->homeUrl;

?>
<div class="main-page">
    <h1><?= $model->title ?></h1>
    <div><?= $model->text() ?></div>
</div>
