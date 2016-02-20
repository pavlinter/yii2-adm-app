<?php

use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model app\models\ContactMsg */

Yii::$app->i18n->disableDot();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Adm::t('app/contact_msg', 'Contact Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="contact-msg-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Adm::t('app/contact_msg', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Adm::t('app/contact_msg', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Adm::t('app/contact_msg', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= Adm::widget('DetailView', [
        'model' => $model,
        'hover' => true,
        'mode' => \kartik\detail\DetailView::MODE_VIEW,
        'attributes' => [
			[
				'attribute' => 'id',
				'format' => 'text',
			],
			[
				'attribute' => 'from_email',
				'format' => 'email',
			],
			[
				'attribute' => 'to_email',
				'format' => 'email',
			],
			[
				'attribute' => 'subject',
				'format' => 'text',
			],
			[
				'attribute' => 'text',
				'format' => 'raw',
			],
			[
				'attribute' => 'created_at',
				'format' => 'text',
			],
			[
				'attribute' => 'updated_at',
				'format' => 'text',
			],
        ],
    ]) ?>

</div>
