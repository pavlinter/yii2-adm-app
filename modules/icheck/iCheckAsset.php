<?php

namespace app\modules\icheck;

use app\assets_b\Asset;

/**
 * Asset bundle for iCheck plug-in for jQuery.
 */
class iCheckAsset extends Asset
{
    public $basePath = '@webroot/modules/icheck/assets';

    public $baseUrl = '@web/modules/icheck/assets';

    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/icheck.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $skinPath = 'css/skins';

    /**
     * @param string $skin
     */
    public function skin($skin = 'all')
    {
        if ((strpos($skin, '-') !== false)) {
            $skin = str_replace('-', '/', $skin);
        }

        if (in_array($skin, ['minimal', 'square', 'flat', 'line', 'polaris', 'futurico'])) {
            $skin .= '/' . $skin;
        }


        $this->css[] = $this->skinPath . '/' . $skin . '.css';
    }

}
