<?php

use app\models\User;
use pavlinter\adm\widgets\GridView;
use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $searchModel app\core\adm\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Yii::$app->i18n->disableDot();
$this->title = Adm::t('user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Adm::t('user', 'Create User'), ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'width' => '70px',
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            [
                'attribute' => 'avatar',
                'width' => '70px',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) {
                    $photoData = Yii::$app->display->getFileImg($model->id, 'user', [
                        'width' => 50,
                        'height' => 50,
                    ], [
                        'dir' => 'main',
                    ]);
                    return Html::a(Html::img($photoData['display'], ['alt' => $model->username]), ['/adm/user/update', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'username',
                'vAlign' => 'middle',
                'hAlign' => 'left',
            ],
            [
                'attribute' => 'email',
                'vAlign' => 'middle',
                'hAlign' => 'left',
                'format' => 'email',
            ],
            [
                'attribute' => 'role',
                'vAlign' => 'middle',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) {
                    return $model::roles($model->role);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> $searchModel::roles(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' =>true ],
                ],
                'filterInputOptions' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],

            ],
            [
                'attribute' => 'status',
                'vAlign' => 'middle',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) {
                    return $model::status($model->status);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> $searchModel::status(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' =>true ],
                ],
                'filterInputOptions' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],

            ],
            [
                'attribute' => 'online',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) {
                    $options = ['class' => 'user-list-online'];
                    if (User::online($model->id)) {
                        Html::addCssClass($options, 'online-color');
                    }
                    $value = User::onlineHtml($model->id, 'online');
                    $value .= $model->online;

                    return Html::tag('div', $value, $options);
                },

            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{login} {view} {update} {delete}',
                'width' => '100px',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if ($model->id == Adm::getInstance()->user->getId()) {
                            return null;
                        }
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    'login' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-sunglasses"></span>', ['/profilelogin/default/login', 'id' => $model->id], [
                            'title' => Yii::t('adm/title', 'Login', ['dot' => false]),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    }
                ],

            ],
        ],
    ]); ?>
</div>

