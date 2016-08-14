<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator app\modules\admgii\generators\module\Generator */
$this->registerJs('
    $("#generator-moduleid").on("keyup", function(e){
        var $el = $(this);
        $("#generator-moduleclass").val($("#generator-moduleclass").attr("data-template").replace("{moduleID}",$el.val()));
    });
');
?>
<div class="module-form">
<?php
    echo $form->field($generator, 'moduleID');
    echo $form->field($generator, 'moduleClass')->textInput(['data' => ['template' => $generator->moduleClass]]);
?>
</div>
