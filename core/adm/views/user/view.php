<?php

use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model pavlinter\adm\models\User */
Yii::$app->i18n->disableDot();
$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Adm::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();

$authAssignments = Adm::getInstance()->manager->createAuthAssignmentQuery('find')->select("item_name")->where(['user_id' => $model->id])->orderBy(['item_name' => SORT_DESC])->asArray()->all();
$rolesStr = '';
foreach ($authAssignments as $authAssignment) {
    $rolesStr .= '<div>' . $this->context->translateAuthItems($authAssignment['item_name']) . '</div>';
}

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Adm::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if (!Adm::getInstance()->user->can('Adm-UpdateOwnUser', $model)) {?>
            <?= Html::a(Adm::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Adm::t('user', 'Are you sure you want to delete this item?', ['dot' => false]),
                    'method' => 'post',
                ],
            ]) ?>
        <?php }?>
    </p>

    <?= Adm::widget('DetailView', [
        'model' => $model,
        'hover' => true,
        'mode' => \kartik\detail\DetailView::MODE_VIEW,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->status($model->status),
            ],
            [
                'attribute' => 'role',
                'label' => Yii::t('modelAdm/user', 'Assignment Role'),
                'format' => 'raw',
                'value' => $rolesStr,
            ],
            [
                'attribute' => 'updated_at',
                'value' => Yii::$app->formatter->asDate($model->updated_at),
            ],
            [
                'attribute' => 'created_at',
                'value' => Yii::$app->formatter->asDate($model->created_at),
            ],
        ],
    ]) ?>

</div>
