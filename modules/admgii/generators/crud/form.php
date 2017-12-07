<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'admAuto');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'moduleID');
echo $form->field($generator, 'enableLanguage')->checkbox();
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'admGrid' => 'Adm GridView',
    'grid' => 'GridView',
    'list' => 'ListView',
]);

echo $form->field($generator, 'languagePanelType')->dropDownList([
    'panelTab' => 'Panel-Tab',
    'panelToggle' => 'Panel-Toggle',
]);


echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');


$this->registerJs('
    $("#generator-admauto").on("keyup", function(){
        var $el = $(this);
        var val = $el.val().toLowerCase();
        var valUpper = val.charAt(0).toUpperCase() + val.slice(1);

        $("#generator-modelclass").val("app\\\models\\\" + valUpper);
        $("#generator-searchmodelclass").val("app\\\models\\\search\\\" + valUpper + "Search");
        $("#generator-controllerclass").val("app\\\modules\\\appadm\\\controllers\\\" + valUpper + "Controller");
        $("#generator-moduleid").val("appadm").prev(".sticky-value").text("appadm");
        $("#generator-messagecategory").val("adm/" + val).prev(".sticky-value").text("adm/" + val);
    });
');
?>

