<?php

namespace app\modules\cloud\assets;

/**
 * Class FineUploaderAsset
 * @link http://fineuploader.com
 */
class FineUploaderAsset extends Asset
{
    public $css = [
        'plugins/fineuploader/fine-uploader-gallery.css',
        'plugins/fineuploader/fine-uploader-new.css',
    ];

    public $js = [
        'plugins/fineuploader/jquery.fine-uploader.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function getUrl($path = null)
    {
        return $this->baseUrl . '/plugins/fineuploader/' . $path;
    }
}