<?php
use app\helpers\Html;
use app\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
\app\modules\admgoogletools\Module::loadGoogleTools();
\app\modules\admlivechat\Module::loadLiveChat();
\app\modules\admevents\Module::loadEvents();

if (Yii::$app->params['html.canonical'] === true) {
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
} else if(Yii::$app->params['html.canonical'] !== false){
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->params['html.canonical']]);
}

if (\app\models\User::checkRequirements()) {
    if (!in_array(Yii::$app->controller->getRoute(), [
        'user/settings/profile',
    ])) {
        Yii::$app->session->setFlash('danger', Yii::t("app", "You need to finish your profile!", ['dot' => false]));
        Yii::$app->response->redirect(['/user/settings/profile']);
        Yii::$app->end();
    }
}

if (!in_array(Yii::$app->controller->getRoute(), [
    'site/login',
    'site/signup',
    'site/request-password-reset',
    'site/reset-password',
    'site/user-approve',
    'user/settings/profile', //потому что после логина идет редирект на профайл
])) {
    Url::remember(Url::current());
}
Html::addCssClass(Yii::$app->params['html.bodyOptions'], Yii::$app->language);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">
    <?php if (isset(Yii::$app->params['fb.app_id'])) {?>
        <meta property="fb:app_id" content="<?= Yii::$app->params['fb.app_id'] ?>" />
        <?php foreach (Yii::$app->params['og'] as $name => $value) {?>
            <meta property="<?= $name ?>" content="<?= $value ?>" />
        <?php }?>
    <?php }?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php if (Yii::$app->user->can('manager-tasks') || Yii::$app->session->get('AdmSpy')) {?>

    <?php } else {?>

        <?php //google analytic ?>

    <?php }?>

    <?php $this->head() ?>
    <?php $this->trigger("head") ?>
</head>
<?= Html::beginTag('body', Yii::$app->params['html.bodyOptions']) ?>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
<?= Html::endTag('body') ?>
</html>
<?php $this->endPage() ?>