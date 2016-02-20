<?php
/**
 * @var array $options
 */

use app\core\elfinder\events\ConnectEvent;
use app\core\elfinder\MyElFinder;

$elfinderPath = Yii::getAlias('@vendor/mihaildev/yii2-elfinder');

require_once($elfinderPath . '/php/elFinderConnector.class.php');
require_once($elfinderPath . '/php/elFinder.class.php');
require_once($elfinderPath . '/php/elFinderVolumeDriver.class.php');
require_once($elfinderPath . '/php/elFinderVolumeLocalFileSystem.class.php');
require_once($elfinderPath . '/php/elFinderVolumeDropbox.class.php');
require_once($elfinderPath . '/php/elFinderVolumeFTP.class.php');
require_once($elfinderPath . '/php/elFinderVolumeMySQL.class.php');
//require_once($elfinderPath . '/php/elFinderVolumeS3.class.php');
require_once($elfinderPath . '/php/elFinderVolumeMySQL.class.php');

$event = new ConnectEvent(['options' => $options]);
Yii::$app->trigger('elfinder_connect_set_options', $event);

$target      = Yii::$app->getRequest()->get('target');
$width      = Yii::$app->getRequest()->get('w');
$height     = Yii::$app->getRequest()->get('h');
$watermark  = Yii::$app->getRequest()->get('watermark');


if ($width && $height) {
    $event->options['bind']['upload.presave'][] = 'Plugin.AutoResize.onUpLoadPreSave';
    $event->options['plugin']['AutoResize'] = [
        'enable' => true,
        'maxWidth'  => $width,
        'maxHeight'  => $height,
        'quality' => 95
    ];
}

if ($watermark) {
    if ($watermark != 1) {
        $source = Yii::getAlias('@webroot/files/') . strtr($watermark, '::', '/');
    } else {
        $source = Yii::getAlias('@webroot/files/watermark.png');
    }

    $event->options['bind']['upload.presave'][] = 'Plugin.Watermark.onUpLoadPreSave';
    $event->options['plugin']['Watermark'] = [
        'source' => Yii::getAlias('@webroot/files/watermark.png'), // Path to Water mark image
        'marginRight' => 5,          // Margin right pixel
        'marginBottom' => 5,          // Margin bottom pixel
        'quality' => 95,         // JPEG image save quality
        'transparency' => 100,         // Water mark image transparency ( other than PNG )
        'targetType' => IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP, // Target image formats ( bit-field )
        'targetMinPixel' => 200         // Target image minimum pixel size
    ];
}

function filterName(&$path, &$name, $src, $elfinder, $volume) {
    //$name = strtolower($name);
    $name = str_replace(' ', '_', $name);
}
$event->options['bind']['upload.presave'][] = 'filterName';

Yii::$app->trigger('elfinder_connect_init', $event);
$elfinder = new MyElFinder($event->options);

// run elFinder
$connector = new elFinderConnector($elfinder);
$connector->run();