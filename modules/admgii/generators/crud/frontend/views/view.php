<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use app\helpers\Url;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

//Yii::$app->i18n->disableDot();
$this->title = $model-><?= $generator->getNameAttribute() ?>;
//Yii::$app->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
Yii::$app->params['breadcrumbs'][] = $this->title;
//Yii::$app->i18n->resetDot();
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Yii::$app->i18n->disableDot() ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, Url::current(['update', <?= $urlParams ?>]), ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, Url::current(['delete', <?= $urlParams ?>]), [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
        <?= "<?= " ?>Yii::$app->i18n->resetDot() ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'hover' => true,
        'mode' => \kartik\detail\DetailView::MODE_VIEW,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo $generator->generateColumnDetailView($column);

    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        echo $generator->generateColumnDetailView($column);
        $format = $generator->generateColumnFormat($column);

    }
}
?>
        ],
    ]) ?>

</div>
