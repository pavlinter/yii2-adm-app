<?php

namespace app\modules\magnific;

use app\assets_b\Asset;

/**
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @link http://dimsemenov.com/plugins/magnific-popup/
 */
class MagnificModalAsset extends Asset
{
    public $sourcePath = '@bower/magnific-popup/dist';

    public $js = [
        'jquery.magnific-popup.min.js',
    ];

    public $css = [
        'magnific-popup.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}