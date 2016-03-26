<?php

use pavlinter\admpages2\Module;

/* @var $this yii\web\View */
/* @var $model app\core\admpages\models\Page */

Module::getInstance()->layout = Module::getInstance()->pageLayout;
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="adm-pages-layout-page">
    <h1><?= $model->title ?></h1>
    <div><?= $model->text ?></div>
</div>
