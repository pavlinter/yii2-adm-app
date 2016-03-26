<?php

/* @var $this yii\web\View */
/* @var $model app\core\admpages\models\Page */

Yii::$app->params['html.canonical'] = Yii::$app->homeUrl;
?>
<div class="main-page">
    <h1><?= $model->title ?></h1>
    <div><?= $model->text ?></div>
</div>
