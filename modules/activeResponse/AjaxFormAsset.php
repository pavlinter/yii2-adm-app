<?php

namespace app\modules\activeResponse;

use Yii;

/**
 * Asset bundle for AjaxFormAsset
 *
 * @since 1.0
 */
class AjaxFormAsset extends \app\assets_b\Asset
{
    public $basePath = '@webroot/modules/activeResponse/assets';

    public $baseUrl = '@web/modules/activeResponse/assets';

    public $js = [
        'js/jquery.form.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
