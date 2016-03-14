<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use pavlinter\adm\Adm;
use app\helpers\ArrayHelper;
use app\helpers\Url;
use yii\helpers\Html;
use mihaildev\elfinder\Assets;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $startPath string */
/* @var $elfinderData array */
<?php if ($generator->getParentColumn()) {?>
/* @var $id_parent boolean|integer */
<?php }?>

Yii::$app->i18n->disableDot();
$this->title = <?= $generator->generateString('Files ' . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . ': ') ?> . ' ' . $model-><?= $generator->getNameAttribute() ?>;
<?php if ($generator->hasBreadcrumbsTree()) {?>
$this->params['breadcrumbs'] = [];
$model::breadcrumbsTree($this->params['breadcrumbs'], $id_parent, ['lastLink' => true]);
array_unshift($this->params['breadcrumbs'], ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index', 'id_parent' => 0]]);
<?php } else {?>
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index'<?= $generator->ifParent(", 'id_parent' => \$id_parent") ?>]];
<?php }?>
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();

Assets::register($this);
Assets::addLangFile(Yii::$app->language, $this);

$this->registerJs('
    var btn = $.fn.button.noConflict();
    $.fn.btn = btn;
    $("#elfinder").elfinder({
        url  : "'. Url::to(ArrayHelper::merge(['/adm/elfinder/connect', 'startPath' => $startPath], $elfinderData)).'",
        lang : "'.Yii::$app->language.'",
        customData: {"'.Yii::$app->request->csrfParam.'":"'.Yii::$app->request->csrfToken.'"},
        rememberLastDir : false,
    });
');
?>
<div class="product-files">

    <h1><?= "<?= " ?> Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?> Html::a(Adm::t('', 'Update'), Url::current(['update', <?= $urlParams ?>]), ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?> Html::a(Adm::t('', 'Delete'), Url::current(['delete', <?= $urlParams ?>]), [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Adm::t('', 'Are you sure you want to delete this item?', ['dot' => false]),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div id="elfinder"></div>
</div>
