<?php

namespace app\assets_b;

use kartik\icons\Icon;
use Yii;

/**
 * Class AppAsset
 */
class AppAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/common.js'
    ];

    public $depends = [
        'app\assets_b\Html5ShivAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        //'yii\bootstrap\BootstrapThemeAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Icon::map(Yii::$app->getView(), Icon::FA);
    }
}
