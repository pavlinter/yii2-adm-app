<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

\app\helpers\Html::removeCssClass(Yii::$app->params['html.bodyOptions'], 'is_frontend');
\app\helpers\Html::addCssClass(Yii::$app->params['html.bodyOptions'], 'is_backend');
?>

<?php $this->beginContent('@webroot/views/layouts/main.php'); ?>

    <?= $content ?>

<?php $this->endContent(); ?>
