<?php
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $type string */
/* @var $message string */

$this->title = Yii::t("app/signup", "User approval", ['dot' => false]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-user-approve">
    <?php
    echo Alert::widget([
        'options' => [
        'class' => 'alert-' . $type,
    ],
        'body' => $message,
    ]);
    ?>
</div>
