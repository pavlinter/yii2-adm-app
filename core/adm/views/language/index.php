<?php

use kartik\grid\GridView;
use pavlinter\adm\Adm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel pavlinter\adm\models\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->i18n->disableDot();
$this->title = Adm::t('language', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
$show = Yii::$app->request->get('show', 0);
?>



<div class="languages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($show) {?>
        <?= Html::a(Adm::t('language', 'Create Language'), ['create'], ['class' => 'btn btn-primary']) ?>
        <?php }?>
        <?= Html::a('!', '#', ['class' => 'btn btn-primary btn-adm-nestable-view']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'nestable' => [
            'id' => 'pages-nestable-grid',
            'btn' => false, //hide btn
            'nameCol' => 'name',
            'parentCol' => false,
            'orderBy' => SORT_ASC,
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value'=> function ($model, $index, $widget) {
                    if ($model->image) {
                        return Html::img($model->image,['style' => 'max-width: 100px;max-height:100px;']);
                    }
                },
                'width' => '100px',
            ],
            'name',
            'code',
            [
                'class' => 'app\widgets\BooleanColumn',
                'attribute' => 'active',
                'update' => ['updated_at' => time()],
                'tableName' => $searchModel::tableName(),
                'visible' => $show,
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'width' => '70px',
            ],

        ],
    ]);?>

</div>
