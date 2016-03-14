<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator app\modules\admgii\generators\table\Generator */

echo $form->field($generator, 'tableName');
echo $form->field($generator, 'isLang')->checkbox();
echo $form->field($generator, 'db');
echo $form->field($generator, 'useTablePrefix')->checkbox();
