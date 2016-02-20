<?php

use app\helpers\Url;
use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactMsgSearch */
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
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'id',
				'format' => 'text',
				'width' => '70px',
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
				'attribute' => 'viewed',
				'width' => '50px',
				'vAlign' => 'middle',
				'hAlign' => 'center',
				'format' => 'raw',
				'value' => function ($model) {

					if ($model->viewed) {
						$label = Html::tag('span', '', [
							'class' => 'ic glyphicon glyphicon-ok text-success',
						]);
					} else {
						$label = Html::tag('span', '', [
							'class' => 'ic glyphicon glyphicon-remove text-danger',
						]);
					}

					return \pavlinter\buttons\AjaxButton::widget([
						'label' => $label,
						'encodeLabel' => false,
						'options' => [
							'class' => 'btn btn-primary',
						],
						'ajaxOptions' => [
							'url' => Url::to('viewed'),
							'data' => [
								'id' => $model->id,
							],
							'done' => 'function(data){
                                if(data.r){
                                	var $icon = $("#" + abId).find(".ic");
                                    $icon.removeAttr("class");
                                    $icon.addClass(data.class);
                                }
                            }',
						],
					]);
				},
			],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

</div>
