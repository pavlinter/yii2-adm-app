<?php
use app\assets_b\AppAsset;
use app\core\admpages\models\Page;
use app\helpers\Html;
use app\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
$appAsset = AppAsset::register($this);
/* @var $i18n \pavlinter\translation\I18N */
$i18n = Yii::$app->getI18n();

\app\modules\admunderconst\Module::loadUnderConstruction($this);

$menus = Page::find()->with(['translations','childs' => function ($q) {
    $q->andWhere(['active' => 1, 'visible' => 1]);
}])->where(['id_parent' => [1,2,3], 'active' => 1, 'visible' => 1])->orderBy(['weight' => SORT_ASC])->all();
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

    /* @var $menu Page*/
    if ($menu->layout == 'category-list') {
        $item['url'] = 'javascript:void(0);';
        /* @var $categoryModel \app\models\Category */
        $categoryModels = \app\models\Category::find()->with(['translation'])->published()->sortBy()->all();
        foreach ($categoryModels as $categoryModel) {
            $item['items'][] = [
                'label' => $categoryModel->getField('name'),
                'url' => $categoryModel->url(),
            ];
        }
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



?>

<?php $this->beginContent('@webroot/views/layouts/base.php'); ?>

<?php \richardfan\widget\JSRegister::begin(['position' => $this::POS_HEAD]) ?>
<script>
    var isMobile = <?= Yii::$app->mobileDetect->isMobile() ? "true" : "false" ?>;
</script>
<?php \richardfan\widget\JSRegister::end() ?>


<div class="">
    <?= $this->render('@app/views/partial/_header', [
        'is_frontend' => true,
    ]) ?>

    <?php $this->trigger('afterHeader'); ?>
    <div class="<?= Yii::$app->params['html.wrapperClass'] ?>">
        <?= $content ?>
    </div>
    <?php $this->trigger('beforeFooter'); ?>
</div>



<?php $this->endContent(); ?>

