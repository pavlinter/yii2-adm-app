<?php

Yii::$container->set('yii\validators\NumberValidator', [
    'class' => 'app\base\validators\NumberValidator',
]);

Yii::$container->set('yii\widgets\ActiveForm', [
    'class' => 'yii\widgets\ActiveForm',
    'fieldClass' => 'app\widgets\ActiveField',
    'scrollToError' => false,
]);

Yii::$container->set('kartik\grid\GridView', [
    'krajeeDialogSettings' => [
        'dialogDefaults' => [
            \kartik\dialog\Dialog::DIALOG_CONFIRM => [
                'type' => \kartik\dialog\Dialog::TYPE_PRIMARY,
                'btnOKClass' => 'btn-primary',
            ],
        ],
    ],
]);