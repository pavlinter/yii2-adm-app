<?php

namespace app\assets_b;

/**
 * Asset bundle for the Twitter bootstrap default theme.
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class BootstrapThemeAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $css = [
        'css/bootstrap-theme.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
