<?php

use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model app\models\ContactMsg */

Yii::$app->i18n->disableDot();
$this->title = Adm::t('app/contact_msg', 'Create Contact Msg');
$this->params['breadcrumbs'][] = ['label' => Adm::t('app/contact_msg', 'Contact Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->resetDot();
?>
<div class="contact-msg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
