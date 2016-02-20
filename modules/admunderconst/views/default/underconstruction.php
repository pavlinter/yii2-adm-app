<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $settings array */
\app\modules\admunderconst\UnderConstAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
    <body style="width: 100%;min-height: 100%;padding: 0;margin: 0;">
    <?php $this->beginBody() ?>
    <table style="width: 100%; height: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="text-align: center;padding: 20px 0;vertical-align: middle;">

            </td>
        </tr>
    </table>
    <div class="und-cont">
        <img src="<?= \app\helpers\Url::to('@web' . $settings['imagePath']) ?>" class="und-cont-img" alt="Under Construction">
    </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>




