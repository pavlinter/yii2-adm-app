<?php

namespace app\modules\cropper;

/**
 * CropperAsset
 *
 * @url https://github.com/fengyuanchen/cropper
 */
class CropperAvatarAsset extends \app\assets_b\Asset
{
    public $basePath = '@webroot/modules/cropper/assets';

    public $baseUrl = '@web/modules/cropper/assets';

    public $css = [
        'css/avatar.css',
    ];

    public $js = [
        'js/avatar.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'app\modules\cropper\CropperAsset',
    ];
}
