<?php
return [
    'bootstrap' => [
        'gii',
    ],
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'model' => [
                    'class' => '\pavlinter\adm\gii\generators\model\Generator',
                ],
                'crud' => [
                    'class' => '\pavlinter\adm\gii\generators\crud\Generator',
                ],
                'module' => [
                    'class' => '\pavlinter\adm\gii\generators\module\Generator',
                ],
            ]
        ],
    ],
];

