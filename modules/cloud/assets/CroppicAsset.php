<?php

namespace app\modules\cloud\assets;


/**
 * Class CroppicAsset
 * @link http://www.croppic.net/
 */
class CroppicAsset extends Asset
{
    public $css = [
        'plugins/croppic/css/croppic.css',
    ];

    public $js = [
        'plugins/croppic/js/jquery.mousewheel.min.js',
        'plugins/croppic/js/croppic.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
