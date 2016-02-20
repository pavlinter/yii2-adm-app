<?php
use app\assets_b\AppAsset;
use app\core\admpages\models\Page;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use app\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
$appAsset = AppAsset::register($this);
/* @var $i18n \pavlinter\translation\I18N */
$i18n = Yii::$app->getI18n();

\app\modules\admunderconst\Module::loadUnderConstruction($this);

$menus = Page::find()->with(['translations','childs'])->where(['id_parent' => [1,2,3], 'active' => 1, 'visible' => 1])->orderBy(['weight' => SORT_ASC])->all();
$Menu1 = [];
$Menu2 = [];
$Menu3 = [];

$baseUrl = Url::getLangUrl();

foreach ($menus as $menu) {
    $item = [];
    $item['label'] = $menu->name;
    if ($menu->type === 'main') {
        $item['url'] = $baseUrl;
    } else {
        $item['url'] = $menu->url();
    }
    if ($menu->childs) {
        foreach ($menu->childs as $child) {
            $item['items'][] = [
                'label' => $child->name,
                'url' => $child->url(),
            ];
        }
    }
    if ($menu->id_parent == 1) {
        $Menu1[] = $item;
    } elseif($menu->id_parent == 2) {
        $Menu2[] = $item;
    } elseif($menu->id_parent == 3) {
        $Menu3[] = $item;
    }
}

if (Yii::$app->user->isGuest) {
    $Menu1[] = [
        'label' => 'Login',
        'url' => ['/site/login']
    ];

    $Menu1[] = [
        'label' => 'Sign up',
        'url' => ['/site/signup']
    ];
    $Menu1[] = [
        'label' => 'Reset Password',
        'url' => ['/site/request-password-reset']
    ];


} else {
    $Menu1[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ];
}
?>

<?php $this->beginContent('@webroot/views/layouts/base.php'); ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);

        echo \app\widgets\Menu::widget([
            'options' => ['class' => 'core-langs'],
            'items' => $i18n->menuItems(),
            'encodeLabels' => false,
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $Menu1,
        ]);

    NavBar::end();
    ?>
</header>

<div class="<?= Yii::$app->params['html.wrapperClass'] ?> container">
    <?= Breadcrumbs::widget([
        'links' => Yii::$app->params['breadcrumbs'],
    ]) ?>

    <?= \app\widgets\Alert::widget() ?>

    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">

    </div>
</footer>

<?php $this->endContent(); ?>

