<?php

use pavlinter\adm\Adm;
use pavlinter\urlmanager\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \pavlinter\adm\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Yii::$app->i18n->disableDot();
$this->title = Adm::t('source-message', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
$show = Yii::$app->request->get('show', 0);

?>


<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="m-b-sm clearfix">
        <?= Html::a(Adm::t('source-message', 'Create Source Message'), ['create'], ['class' => 'btn btn-primary']) ?>

        <div class="btn-group inline-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= Adm::t('source-message', 'Actions') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li class="<?= (Yii::$app->request->get('is-public') ? 'active' : '') ?>">
                    <?php if (Yii::$app->request->get('is-public')) {?>
                        <?= Html::a(Adm::t('source-message', 'Only not public(disable)'), Url::current(['is-public' => null])) ?>
                    <?php } else {?>
                        <?= Html::a(Adm::t('source-message', 'Only not public'), Url::current(['is-public' => 1])) ?>
                    <?php }?>
                </li>
                <li class="<?= (Yii::$app->request->get('is-empty') ? 'active' : '') ?>">
                    <?php if (Yii::$app->request->get('is-empty')) {?>
                        <?= Html::a(Adm::t('source-message', 'Only not translation(disable)'), Url::current(['is-empty' => null])) ?>
                    <?php } else {?>
                        <?= Html::a(Adm::t('source-message', 'Only not translation'), Url::current(['is-empty' => 1])) ?>
                    <?php }?>
                </li>
                <li role="separator" class="divider"></li>
                <li><?= Html::a(Adm::t('source-message', 'Load All Translation'), ['load-translations'], ['data-method' => 'post']) ?></li>
                <?php if ($show) {?>
                    <li><?= Html::a(Adm::t('source-message', 'Export Translation'), ['/appadm/export/export-translations'], ['data-method' => 'post']) ?></li>
                <?php }?>
            </ul>
        </div>
    </div>



    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'format' => 'text',
                'width' => '70px',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'category',
                'hAlign' => 'left',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'message',
                'hAlign' => 'left',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'translation',
                'format' => 'raw',
                'value'=> function ($model, $index, $widget) {
                    return Yii::t($model->category,$model->message,['dot' => true]);
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'width' => '70px',
            ],
        ],
    ]); ?>

</div>
