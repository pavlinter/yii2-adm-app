<?php

Yii::$container->set('yii\validators\NumberValidator', [
    'class' => 'app\base\validators\NumberValidator',
]);

Yii::$container->set('yii\widgets\ActiveForm', [
    'class' => 'yii\widgets\ActiveForm',
    'fieldClass' => 'app\widgets\ActiveField',
    'scrollToError' => false,
]);