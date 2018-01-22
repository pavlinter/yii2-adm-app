<?php

namespace app\modules\cloud\assets;


/**
 * Asset bundle for DropZoneTheme
 *
 */
class DropZoneThemeAsset extends \app\assets_b\Asset
{
    public $basePath = '@webroot/modules/cloud/assets/common';

    public $baseUrl = '@web/modules/cloud/assets/common';

    public $css = [];

    /**
     * @param $name
     */
    public function addTheme($name)
    {
        $this->css[] = 'css/dropzone_' . $name . '.css';
    }
}