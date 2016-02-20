<?php
/**
 * @param $config array
 * @return array
 */
return function ($config) {
    $startPath = Yii::$app->request->get('startPath');
    if ($startPath) {
        $startPath = Yii::getAlias('@webroot') . '/files' . DIRECTORY_SEPARATOR . strtr($startPath, '::', '/');
    }
    $config['roots'] = [];
    if(Yii::$app->user->can('Adm-FilesRoot')) {
        $config['roots'][] = [
            'baseUrl'=>'@web',
            'basePath'=>'@webroot',
            'path' => 'files',
            'name' => 'Files',
            'options' => [
                'startPath' => $startPath,
                'uploadMaxSize' => '1G',
                'attributes' => [
                    [
                        'pattern' => '#.gitignore$#i',
                        'read' => false,
                        'write' => false,
                        'hidden' => true,
                        'locked' => false
                    ]
                ],
            ],
        ];
        $config['roots'][] = [
            'baseUrl'=>'@web',
            'basePath'=>'@webroot',
            'path' => 'assets_b/common/images',
            'name' => 'Css Images',
            'options' => [
                'uploadMaxSize' => '20M',
                'attributes' => [
                    [
                        'pattern' => '#.gitignore$#i',
                        'read' => false,
                        'write' => false,
                        'hidden' => true,
                        'locked' => false
                    ]
                ],
            ],
        ];
    } elseif(Yii::$app->user->can('Adm-FilesAdmin')) {
        $config['roots'][] = [
            'baseUrl'=>'@web',
            'basePath'=>'@webroot',
            'path' => 'files/data',
            'name' => 'Data',
            'options' => [
                'startPath' => $startPath,
                'uploadMaxSize' => '1G',
                'attributes' => [
                    [
                        'pattern' => '#.gitignore$#i',
                        'read' => false,
                        'write' => false,
                        'hidden' => true,
                        'locked' => false
                    ]
                ],
            ],
        ];
    }
    return $config;
};
