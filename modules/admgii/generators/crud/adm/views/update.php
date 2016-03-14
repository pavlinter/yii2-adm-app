<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
<?php
if ($generator->enableI18N) {
    echo "use pavlinter\\adm\\Adm;";
}
?>


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
<?php if ($generator->getParentColumn()) {?>
/* @var $id_parent boolean|integer */
<?php }?>

Yii::$app->i18n->disableDot();
$this->title = <?= $generator->generateString('Update ' . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . ': ') ?> . ' ' . $model-><?= $generator->getNameAttribute() ?>;
<?php if ($generator->hasBreadcrumbsTree()) {?>
$this->params['breadcrumbs'] = [];
$model::breadcrumbsTree($this->params['breadcrumbs'], $id_parent, ['lastLink' => true]);
array_unshift($this->params['breadcrumbs'], ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index', 'id_parent' => 0]]);
<?php } else {?>
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index'<?= $generator->ifParent(", 'id_parent' => \$id_parent") ?>]];
<?php }?>
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
<?php if ($generator->getParentColumn()) {?>
        'id_parent' => $id_parent,
<?php }?>
    ]) ?>

</div>
