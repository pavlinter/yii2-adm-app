<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $generators \app\modules\admgii\Generator[] */
/* @var $activeGenerator \app\modules\admgii\Generator */
/* @var $content string */

$generators = Yii::$app->controller->module->generators;
$activeGenerator = Yii::$app->controller->generator;
?>
<?php $this->beginContent('@admgii/views/layouts/main.php'); ?>
<div class="row">
    <div class="col-md-3 col-sm-4">
        <div class="list-group">
            <?php
            foreach ($generators as $id => $generator) {
                $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . Html::encode($generator->getName());
                if ($generator === $activeGenerator) {
                    $class = 'list-group-item active';
                } else {
                    $class = 'list-group-item';
                    if (in_array($id, ['crudmodule', 'modelmodule', 'table'])) {
                        $class .= ' list-group-item-warning';
                    }
                }
                if (in_array($id, ['table'])) {
                    echo Html::a($label, ['default/table', 'id' => $id], [
                        'class' => $class,
                    ]);
                } else {
                    echo Html::a($label, ['default/view', 'id' => $id], [
                        'class' => $class,
                    ]);
                }


            }
            ?>
        </div>
    </div>
    <div class="col-md-9 col-sm-8">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>
