<?php

use app\helpers\Url;
use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ContactMsgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->i18n->disableDot();
$this->title = Adm::t('app/contact_msg', 'Contact Msgs');
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="contact-msg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Adm::t('app/contact_msg', 'Create Contact Msg'), ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			[
				'attribute' => 'id',
				'format' => 'text',
				'width' => '70px',
				'hAlign' => 'center',
			],
			[
				'attribute' => 'from_email',
				'format' => 'email',
			],
			[
				'attribute' => 'subject',
				'format' => 'raw',
				'value' => function ($model) {
				    return Html::a($model->subject, ['view', 'id' => $model->id]);
				}
			],
			[
				'attribute' => 'text',
				'format' => 'raw',
			],
			[
				'attribute' => 'created_at',
				'format' => 'text',
				'width' => '150px',
				'vAlign' => 'middle',
				'hAlign' => 'center',
			],
			[
				'class' => 'app\widgets\BooleanColumn',
				'attribute' => 'viewed',
				'tableName' => $searchModel::tableName(),
			],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

</div>
