<?php
use app\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
\app\modules\admgoogletools\Module::loadGoogleTools();
\app\modules\admlivechat\Module::loadLiveChat();
if (Yii::$app->params['html.canonical'] === true) {
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
} else if(Yii::$app->params['html.canonical'] !== false){
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->params['html.canonical']]);
}
if (!in_array(Yii::$app->controller->getRoute(), [
    'site/login',
    'site/signup',
    'site/request-password-reset',
    'site/reset-password',
    'site/user-approve',
])) {
    Url::remember(Url::current());
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?= Html::beginTag('body', Yii::$app->params['html.bodyOptions']) ?>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
<?= Html::endTag('body') ?>
</html>
<?php $this->endPage() ?>
