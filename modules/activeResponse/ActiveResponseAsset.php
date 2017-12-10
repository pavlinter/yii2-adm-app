<?php

namespace app\modules\activeResponse;

use Yii;


/**
 * Asset bundle for ActiveResponse
 *
 * @see http://github.com/srusakov/yii2-activeresponse
 * @since 1.0
 */
class ActiveResponseAsset extends \app\assets_b\Asset
{
    public $basePath = '@webroot/modules/activeResponse/assets';

    public $baseUrl = '@web/modules/activeResponse/assets';

    public $js = [
        'js/activeResponse.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

    /**
     * @param bool $loadGrowl
     * @param null $view
     * @return self
     */
    public function run($loadGrowl = true, $view = null)
    {
        if ($view === null) {
            $view = Yii::$app->getView();
        }
        //$view->registerJs("window.actireresponse_root_url='" . Url::to(['/']) . "';");
        if ($loadGrowl) {
            //http://bootstrap-notify.remabledesigns.com/
            \kartik\growl\GrowlAsset::register($view);
        }
        return $this;
    }

    /**
     * @param null $view
     * @return self
     */
    public function ajaxForm($view = null)
    {
        if ($view === null) {
            $view = Yii::$app->getView();
        }
        AjaxFormAsset::register($view);
        return $this;
    }
}
