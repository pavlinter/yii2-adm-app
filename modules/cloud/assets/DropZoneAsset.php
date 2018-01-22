<?php

namespace app\modules\cloud\assets;


/**
 * Asset bundle for DropZone Widget
 *
 * "bower-asset/dropzone": "4.3.*"
 * @link http://www.dropzonejs.com/
 */
class DropZoneAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/dropzone';


    public $js = [
        "dist/min/dropzone.min.js",
    ];

    public $css = [
        "dist/min/dropzone.min.css",
    ];

}