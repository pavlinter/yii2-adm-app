<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use app\helpers\Url;


//\app\modules\admunderconst\Module::loadUnderConstruction($this);
//$userAsset = \app\assets_b\UserAsset::register($this);
//$baseUrl = Url::getLangUrl();
?>

<?php $this->beginContent('@userRoot/views/layouts/base.php'); ?>

    <?= $content ?>

<?php $this->endContent(); ?>
