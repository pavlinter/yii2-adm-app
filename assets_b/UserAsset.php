<?php

namespace app\assets_b;

use kartik\icons\Icon;
use Yii;

/**
 * Class AppAsset
 */
class UserAsset extends Asset
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
        'app\assets_b\AnimateAsset',
        'app\assets_b\Html5ShivAsset',
        'app\assets_b\WebUIPopoverAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        //'yii\bootstrap\BootstrapThemeAsset',
        //'yii\web\JqueryAsset',
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
