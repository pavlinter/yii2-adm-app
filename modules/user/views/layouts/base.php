<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

\app\helpers\Html::addCssClass(Yii::$app->params['html.bodyOptions'], 'is_backend')
?>

<?php $this->beginContent('@webroot/views/layouts/base.php'); ?>

    <?= $content ?>

<?php $this->endContent(); ?>
