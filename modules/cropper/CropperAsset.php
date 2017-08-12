<?php

namespace app\modules\cropper;

/**
 * CropperAsset
 *
 * @url https://github.com/fengyuanchen/cropper
 */
class CropperAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'cropper/dist/cropper.min.css',
    ];
    public $js = [
        'cropper/dist/cropper.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
