<?php

namespace app\assets_b;

/**
 * Class AdmAsset
 */
class AdmAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $css = [
        'css/adm.css',
    ];

    public $depends = [
        'pavlinter\adm\AdmAsset',
    ];

    /**
     * @inheritdoc
     */
    /*public function init()
    {
        parent::init();
    }*/
}
