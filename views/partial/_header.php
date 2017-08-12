<?php

/**
 * @var \yii\web\View $this
 */
use app\helpers\SiteHelper;
use app\helpers\Url;
use app\models\User;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $i18n \pavlinter\translation\I18N */
$i18n = Yii::$app->getI18n();
$baseUrl = Url::getLangUrl();

if (!isset($is_frontend)) {
    $is_frontend = false;
}

if ($is_frontend) {
    $userAsset = \app\assets_b\AppAsset::register($this);
} else {
    $userAsset = \app\assets_b\UserAsset::register($this);
}


$topMenu = SiteHelper::getPageMenu('menu1');

if (Yii::$app->user->isGuest) {
    $topMenu[] = [
        'label' => Yii::t("app/menu", "Login", ['dot' => false]),
        'url' => ['/site/login'],
        'options' => [
            //'class' => 'action-login-popup',
        ],
    ];

    $topMenu[] = [
        'label' => Yii::t("app/menu", "Sign up", ['dot' => false]),
        'url' => ['/site/signup']
    ];
    $topMenu[] = [
        'label' => Yii::t("app/menu", "Reset Password", ['dot' => false]),
        'url' => ['/site/request-password-reset']
    ];
}


?>


<?php
NavBar::begin([
    'brandLabel' => 'My Company',
    'brandUrl' => $baseUrl,
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);
echo \app\widgets\Menu::widget([
    'options' => ['class' => 'core-langs'],
    'items' => $i18n->menuItems(),
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => $topMenu,
]);

?>

<?php if (!Yii::$app->user->isGuest) {?>

    <?php if ($is_frontend) {?>
        <div class="now-online"><?= Yii::t("app", "Now online: {count}", ['count' => SiteHelper::userOnline()]); ?></div>
    <?php }?>


    <ul class="nav navbar-nav navbar-right navbar-user-ul">

        <li class="dropdown">
            <a href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                <i class="fa fa-bell-o"></i>
                <span class="label notifications-count"></span>
            </a>
            <ul class="dropdown-menu media-list pull-right animated fadeInDown notification-menu">
                <?php /* <li class="dropdown-header">Notifications (5)</li> */?>
                <li class="dropdown-header notifications-empty" style="display: none;"><?= Yii::t("user", 'empty Notifications', ['dot' => false]) ?></li>
                <li class="notifications-start"></li>

                <li class="dropdown-footer text-center notifications-more">
                    <a href="javascript:void(0);" class="action-prev-notification"><?= Yii::t("user", 'Readmore Notifications', ['dot' => false]) ?></a>
                </li>
            </ul>
        </li>
        <li class="dropdown navbar-user">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= User::ownAvatar() ?>" title="<?= Yii::$app->user->getId() ?>" class="avatar-icon" alt="" />
                <span class="hidden-xs"><?= User::identity()->getField('display') ?></span> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInDown">
                <li class="arrow"></li>
                <li><a href="<?= Url::to(['/user/settings/profile']) ?>"><?= Yii::t("app/menu", 'Edit Profile', ['dot' => false]) ?></a></li>
                <li class="divider"></li>
                <li><a href="<?= Url::to(['/site/logout']) ?>" data-method="post"><?= Yii::t("app/menu", 'Logout ({username})', ['dot' => false, 'username' => User::identity()->getField('display')]) ?></a></li>
            </ul>
        </li>
    </ul>

<?php }?>

<?php
NavBar::end();
?>