<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use yii\widgets\Breadcrumbs;

$userAsset = \app\assets_b\UserAsset::register($this);

?>

<?php $this->beginContent('@userRoot/views/layouts/main.php'); ?>

    <div class="container">
        <?= \app\widgets\Alert::widget(); ?>

        <?= Breadcrumbs::widget([
            'tag' => 'ol',
            'options' => ['class' => 'breadcrumb pull-right'],
            'links' => Yii::$app->params['breadcrumbs'],
        ]) ?>

        <?= $content ?>
    </div>

<?php $this->endContent(); ?>
