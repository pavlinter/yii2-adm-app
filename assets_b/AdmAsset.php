<?php

namespace app\assets_b;

use yii\web\AssetBundle;

/**
 * Class AdmAsset
 */
class AdmAsset extends AssetBundle
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $css = [
        'css/adm.css',
    ];

    public $depends = [
        'pavlinter\adm\AdmAsset',
    ];
}
