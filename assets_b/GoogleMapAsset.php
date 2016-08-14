<?php

namespace app\assets_b;

use kartik\icons\Icon;
use Yii;

/**
 * Class GoogleMapAsset
 */
class GoogleMapAsset extends Asset
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app->params['google.map.api.key']) {
            $this->js[] = 'https://maps.googleapis.com/maps/api/js?sensor=false&language='. Yii::$app->language. '&key=' . Yii::$app->params['google.map.api.key'];
        } else {
            $this->js[] = 'https://maps.googleapis.com/maps/api/js?sensor=false&language='. Yii::$app->language;
        }
    }
}
