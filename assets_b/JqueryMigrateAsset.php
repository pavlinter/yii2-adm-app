<?php

namespace app\assets_b;


/**
 * Class JqueryMigrateAsset
 */
class JqueryMigrateAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $js = [
        'js/jquery-migrate-1.2.1.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
