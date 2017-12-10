<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
if ($generator->indexWidgetType === 'admGrid') {
    $generator->indexWidgetType = 'grid';
}


echo "<?php\n";
?>

use yii\helpers\Html;
use app\helpers\Url;
<?php
    if($generator->indexWidgetType === 'grid'){
        echo "use kartik\\grid\\GridView;";
    } elseif($generator->indexWidgetType === 'list') {
        echo "use yii\\widgets\\ListView;";
    }
?>


/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->i18n->disableDot();
$this->title = '';//<?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
Yii::$app->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' || $generator->indexWidgetType === 'admGrid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, Url::current(['create']), ['class' => 'btn btn-primary']) ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'){ ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'export' => false,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'kartik\grid\SerialColumn'],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo $generator->generateColumn($name, true);
        } else {
            echo "\t\t\t/*\n";
            echo $generator->generateColumn($name, true);
            echo "\t\t\t*/\n";
        }
    }
} else {
    $afterId = null;
    $name = $generator->getNameAttribute();
    foreach ($tableSchema->columns as $column) {
        if($generator->checkCol($column->name, ['comment' => 'parent']) || $generator->checkCol($column->name, ['comment' => 'id_parent'])){
            continue;
        }

        if($column->name == 'id'){
            $afterId = true;
        } else if ($afterId && $name != 'id') {
            echo $generator->generateColumn($name, true);
            $afterId = false;
            if ($name == $column->name) {
                continue;
            }
        }

        if (++$count < 6) {
            echo $generator->generateColumn($column, true);
        } else {
            echo "\t\t\t/*\n";
            echo $generator->generateColumn($column, true);
            echo "\t\t\t*/\n";
        }
    }
}
?>
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons'  => [
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
<?php } else { ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php } ?>

</div>
