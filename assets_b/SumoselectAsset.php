<?php

namespace app\assets_b;

use Yii;
use yii\web\AssetBundle;

/**
 * Class SumoselectAsset
 */
class SumoselectAsset extends AssetBundle
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $css = [
        'plugins/jquery.sumoselect/css/sumoselect.css',
    ];

    public $js = [
        'plugins/jquery.sumoselect/js/jquery.sumoselect.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
    }
}
