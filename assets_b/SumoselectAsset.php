<?php

namespace app\assets_b;

use Yii;

/**
 * Class SumoselectAsset
 */
class SumoselectAsset extends Asset
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

    /**
     * @inheritdoc
     */
    /*public function init()
    {
        parent::init();
    }*/
}
