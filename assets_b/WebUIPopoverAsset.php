<?php
namespace app\assets_b;

use Yii;
use yii\web\AssetBundle;

/**
 * WebUIPopover bundle
 * @link https://github.com/sandywalker/webui-popover/blob/master/README.md
 */
class WebUIPopoverAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/webui-popover/dist/';

    public $js = [
        'jquery.webui-popover.js'
    ];

    public $css = [
        'jquery.webui-popover.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
