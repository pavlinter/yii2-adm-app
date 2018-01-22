<?php

namespace app\modules\cloud\assets;

use kartik\icons\Icon;
use Yii;
use yii\web\AssetBundle;

/**
 * Class Asset
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@app/modules/cloud/assets/common';

    /*public $basePath = '@webroot/modules/cloud/assets/common';

    public $baseUrl = '@web/modules/cloud/assets/common';*/

    public $css = [
        'css/style.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        Icon::map(Yii::$app->getView(), Icon::FA);
        parent::init();
    }
}
