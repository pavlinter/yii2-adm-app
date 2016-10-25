<?php

namespace app\modules\slidebars;
use app\assets_b\Asset;


/**
 * SlidebarsAsset
 * @link https://www.adchsm.com/slidebars/
 */
class SlidebarsAsset extends Asset {

	public $basePath = '@webroot/modules/slidebars/assets';

	public $baseUrl = '@web/modules/slidebars/assets';

	public $css = [
		'css/slidebars.css'
	];

	public $js = [
		'js/slidebars.js'
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];
}
