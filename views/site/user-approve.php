<?php
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $type string */
/* @var $message string */

$this->title = Yii::t("app/signup", "User approval", ['dot' => false]);

Yii::$app->params['breadcrumbs'][] = Yii::t("app/signup", "User approval", ['dot' => true,]);
?>
<div class="site-user-approve">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <?php
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-' . $type,
                ],
                'body' => $message,
            ]);
            ?>
        </div>
    </div>
</div>
