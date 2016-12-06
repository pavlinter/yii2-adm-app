<?php

namespace app\modules\magnific;

use app\assets_b\Asset;

/**
 * @author Pavels Radajevs <pavlinter@gmail.com>
 */
class MagnificThemeAsset extends Asset
{
    public $basePath = '@webroot/modules/magnific/assets';

    public $baseUrl = '@web/modules/magnific/assets';

    public $css = [
        'css/magnific.css',
    ];
}