<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use app\helpers\Url;
<?php
    if ($generator->indexWidgetType === 'admGrid' || $generator->enableI18N) {
        echo "use pavlinter\\adm\\Adm;";
    }
    if($generator->indexWidgetType === 'grid'){
        echo "\nuse yii\\grid\\GridView;";
    } elseif($generator->indexWidgetType === 'list') {
        echo "\nuse yii\\widgets\\ListView;";
    }
?>


/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
<?php if ($generator->getParentColumn()) {?>
/* @var $id_parent boolean|integer */
<?php }?>

Yii::$app->i18n->disableDot();
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
<?php if ($generator->hasBreadcrumbsTree()) {?>
$this->params['breadcrumbs'] = [];
$searchModel::breadcrumbsTree($this->params['breadcrumbs'], $id_parent);
array_unshift($this->params['breadcrumbs'], ['label' => $this->title, 'url' => ['index', 'id_parent' => 0]]);
<?php } else {?>
$this->params['breadcrumbs'][] = $this->title;
<?php }?>
Yii::$app->i18n->resetDot();
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' || $generator->indexWidgetType === 'admGrid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, Url::current(['create']), ['class' => 'btn btn-primary']) ?>
<?php if ($generator->getParentColumn()) {?>
        <?= "<?= " ?> Html::a(Adm::t('', 'All items'), [''], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?> Html::a(Adm::t('', 'Front items'), ['', 'id_parent' => 0], ['class' => 'btn btn-primary']) ?>
<?php }?>
<?php if ($generator->checkCol('weight', ['comment' => 'weight']) && $generator->indexWidgetType === 'admGrid') {?>
        <?= "<?= " ?> Html::a('!', '#', ['class' => 'btn btn-primary btn-adm-nestable-view']) ?>
<?php }?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'){ ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'kartik\grid\SerialColumn'],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
<?php } elseif($generator->indexWidgetType === 'admGrid') { ?>
    <?= "<?= " ?>Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
<?php if ($generator->checkCol('weight', ['comment' => 'weight'])) {?>
        'nestable' => [
            /* @var \pavlinter\adm\widgets\GridNestable */
            'id' => 'pages-nestable-grid',
            'btn' => false, //hide btn
            'nameCol' => '<?= $generator->getNameAttribute() ?>',
            'parentCol' => <?= $generator->getParentColumn() ? "'" . $generator->getParentColumn() . "'": "false" ?>,
            'orderBy' => SORT_DESC,
        ],
<?php }?>
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            //['class' => 'kartik\grid\SerialColumn'],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo $generator->generateColumn($name);
        } else {
            echo "\t\t\t/*\n";
            echo $generator->generateColumn($name);
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
            echo $generator->generateColumn($name);
            $afterId = false;
            if ($name == $column->name) {
                continue;
            }
        }

        if (++$count < 6) {
            echo $generator->generateColumn($column);
        } else {
            echo "\t\t\t/*\n";
            echo $generator->generateColumn($column);
            echo "\t\t\t*/\n";
        }
    }
}
?>
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{copy} {update}<?= $generator->ifParent(" {subsection}") ?> {files} {delete}',
                'width' => '120px',
                'buttons' => [
                    'copy' => function ($url, $model, $key) {
                        $url = Url::current(['create', 'copy_id' => $model->id]);
                        return Html::a('<span class="fa fa-copy"></span>', $url, [
                            'title' => <?= $generator->generateString('Copy', ['dot' => false, 'subcategory' => 'titles', 'dropN' => true,]) ?>,
                            'data-pjax' => '0',
                        ]);
                    },
                    'files' => function ($url, $model, $key) {
                        $url = Url::current(['files', 'id' => $model->id]);
                        return Html::a('<span class="fa fa-cloud-download"></span>', $url, [
                            'title' => <?= $generator->generateString('Files', ['dot' => false, 'subcategory' => 'titles', 'dropN' => true,]) ?>,
                            'data-pjax' => '0',
                        ]);
                    },
<?php if ($generator->getParentColumn()) {?>
                    'subsection' => function ($url, $model, $key) {
                        $url = Url::current(['id_parent' => $model->id]);
                        return Html::a('<span class="fa fa-plus-circle"></span>', $url, [
                            'title' => Adm::t('titles', 'Subsection', ['dot' => false]),
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        $url = Url::current(['update', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        $url = Url::current(['delete', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete', ['dot' => false]),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?', ['dot' => false]),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    'view' => function ($url, $model, $key) {
                        $url = Url::current(['view', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'View', ['dot' => false]),
                            'data-pjax' => '0',
                            'target' => '_blank'
                        ]);
                    },


<?php }?>
                ],
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
