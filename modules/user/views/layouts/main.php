<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use app\helpers\Url;
use yii\helpers\Html;

\app\modules\admunderconst\Module::loadUnderConstruction($this);
$userAsset = \app\assets_b\UserAsset::register($this);
$baseUrl = Url::getLangUrl();

Html::addCssClass(Yii::$app->params['html.bodyOptions'], 'boxed-layout')
?>

<?php $this->beginContent('@userRoot/views/layouts/base.php'); ?>


<div>

    <?= $this->render('@app/views/partial/_header') ?>

    <?php $this->trigger('afterHeader'); ?>

    <?= $content ?>

    <?php $this->trigger('beforeFooter'); ?>

</div>

<?php $this->endContent(); ?>
