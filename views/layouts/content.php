<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets_b\AppAsset;
use yii\widgets\Breadcrumbs;

$appAsset = AppAsset::register($this);
?>

<?php $this->beginContent('@webroot/views/layouts/main.php'); ?>
<div class="container">
    <?= Breadcrumbs::widget([
        'encodeLabels' => false,
        'links' => Yii::$app->params['breadcrumbs'],
    ]) ?>
    <?= \app\widgets\Alert::widget(); ?>
    <?= $content ?>
</div>
<?php $this->endContent(); ?>

