<?php

namespace app\assets_b;


/**
 * Class Html5ShivAsset
 */
class Html5ShivAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $jsOptions = [
        'condition' => 'lt IE 9',
    ];

    public $js = [
        'js/html5shiv.min.js',
    ];

    /**
     * @inheritdoc
     */
    /*public function init()
    {
        parent::init();
    }*/
}
