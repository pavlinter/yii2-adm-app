<?php

namespace app\assets_b;

use Yii;

/**
 * Class GoogleMapAsset
 * @link https://github.com/marioestrada/jQuery-gMap
 *
 * create key https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,places_backend&reusekey=true
 *
 * example:
 *  $('#googlemaps').gMap({
 *      maptype: 'ROADMAP',
 *      controls: {
 *          panControl: false,
 *          zoomControl: false,
 *          mapTypeControl: false,
 *          scaleControl: false,
 *          streetViewControl: false,
 *          overviewMapControl: false
 *      },
 *      zoom: 13,
 *      mapTypeControl: false,
 *      markers: [
 *      {
 *          latitude: parseFloat('<?= Yii::t("app/contacts/map/latitude", "-2.2014", ['dot' => false]); ?>'),
 *          longitude: parseFloat('<?= Yii::t("app/contacts/map/longitude", "-80.9763", ['dot' => false]); ?>'),
 *          //address: '<?= Yii::t("app/contacts", "New York, 45 Park Avenue", ['dot' => false]); ?>', // Your Adress Here
 *          html: '<?= Yii::t("app/contacts", '<strong>Our Office</strong><br>45 Park Avenue, Apt. 303 </br>New York, NY 10016', ['dot' => false, 'br' => false]); ?>',
 *          popup: false,
 *          icon: {
 *              image: "<?= $appAsset->baseUrl ?>/images/map-icon.png",
 *              iconsize: [41, 53],
 *              iconanchor: [20,53]
 *          }
 *      }
 *      ],
 *  });
 */
class GoogleMapAsset extends Asset
{
    public $basePath = '@webroot/assets_b/common';

    public $baseUrl = '@web/assets_b/common';

    public $js = [
        'js/jquery.gmaps.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app->params['google.map.api.key']) {
            $this->js[] = 'https://maps.googleapis.com/maps/api/js?language='. Yii::$app->language. '&key=' . Yii::$app->params['google.map.api.key'];
        } else {
            $this->js[] = 'https://maps.googleapis.com/maps/api/js?sensor=false&language='. Yii::$app->language;
        }
    }
}
