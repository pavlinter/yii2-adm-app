<?php

use app\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\core\admpages\models\Page */

Yii::$app->params['html.canonical'] = Url::to('', true);
$appAsset = \app\assets_b\AppAsset::register($this);

//$this->context->layout = '/main';
/*
Yii::$app->params['og']['og:title'] = $this->title;
Yii::$app->params['og']['og:type'] = 'website';
Yii::$app->params['og']['og:url'] = Yii::$app->params['html.canonical'];
Yii::$app->params['og']['og:image'] = Url::to('@web/files/banners/main_banner_' . Yii::$app->language . '.jpg', true);
Yii::$app->params['og']['og:description'] = $model->description;
*/


?>

<div class="main-page container">
    <h1><?= $model->title ?></h1>
    <div><?= $model->text ?></div>

    <?php
    echo \app\widgets\AjaxConfirmButton::widget([
        'url' => Url::current(),
        'label' => "Confirm Button",
        'content' => "Sure?",
        //'warningContent' => false,
        'ajaxOptions' => [
            'data' => [
                'id' => 1,
            ],
        ],
    ]);
    ?>
</div>

<?= \app\widgets\Likes::widget([
    'data' => [
        'orientation' => 'fixed-left',
    ],
]); ?>