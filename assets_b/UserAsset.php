<?php

namespace app\assets_b;

use kartik\icons\Icon;
use Yii;

/**
 * Class AppAsset loaded only when logined
 */
class UserAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $js = [
        'js/user.js'
    ];

    public $depends = [

    ];
}
