<?php

namespace app\modules\icheck\widgets;

use app\modules\icheck\iCheckAsset;
use Yii;
use yii\helpers\Json;
use yii\widgets\InputWidget;


/**
 * Class RadioList
 */
class iCheckOptions extends InputWidget
{
	const SKIN_MINIMAL 			= 'minimal';
	const SKIN_MINIMAL_RED 		= 'minimal-red';
	const SKIN_MINIMAL_GREEN 	= 'minimal-green';
	const SKIN_MINIMAL_BLUE 	= 'minimal-blue';
	const SKIN_MINIMAL_AERO 	= 'minimal-aero';
	const SKIN_MINIMAL_GREY 	= 'minimal-grey';
	const SKIN_MINIMAL_ORANGE 	= 'minimal-orange';
	const SKIN_MINIMAL_YELLOW 	= 'minimal-yellow';
	const SKIN_MINIMAL_PINK 	= 'minimal-pink';
	const SKIN_MINIMAL_PURPLE 	= 'minimal-purple';

	const SKIN_SQUARE 			= 'square';
	const SKIN_SQUARE_RED 		= 'square-red';
	const SKIN_SQUARE_GREEN 	= 'square-green';
	const SKIN_SQUARE_BLUE 		= 'square-blue';
	const SKIN_SQUARE_AERO 		= 'square-aero';
	const SKIN_SQUARE_GREY 		= 'square-grey';
	const SKIN_SQUARE_ORANGE 	= 'square-orange';
	const SKIN_SQUARE_YELLOW 	= 'square-yellow';
	const SKIN_SQUARE_PINK 		= 'square-pink';
	const SKIN_SQUARE_PURPLE 	= 'square-purple';

	const SKIN_FLAT 			= 'flat';
	const SKIN_FLAT_RED 		= 'flat-red';
	const SKIN_FLAT_GREEN 		= 'flat-green';
	const SKIN_FLAT_BLUE 		= 'flat-blue';
	const SKIN_FLAT_AERO 		= 'flat-aero';
	const SKIN_FLAT_GREY 		= 'flat-grey';
	const SKIN_FLAT_ORANGE 		= 'flat-orange';
	const SKIN_FLAT_YELLOW 		= 'flat-yellow';
	const SKIN_FLAT_PINK 		= 'flat-pink';
	const SKIN_FLAT_PURPLE 		= 'flat-purple';

	const SKIN_LINE 			= 'line';
	const SKIN_LINE_RED 		= 'line-red';
	const SKIN_LINE_GREEN 		= 'line-green';
	const SKIN_LINE_BLUE 		= 'line-blue';
	const SKIN_LINE_AERO 		= 'line-aero';
	const SKIN_LINE_GREY 		= 'line-grey';
	const SKIN_LINE_ORANGE 		= 'line-orange';
	const SKIN_LINE_YELLOW 		= 'line-yellow';
	const SKIN_LINE_PINK 		= 'line-pink';
	const SKIN_LINE_PURPLE 		= 'line-purple';

	const SKIN_POLARIS 			= 'polaris';

	const SKIN_FUTURICO 		= 'futurico';

	public $skin = self::SKIN_MINIMAL;

	public $defaultPluginOptions = [
		'increaseArea' => '20%',
		'cursor' => true,
	];

	public function setSkin()
	{
		$view = $this->getView();
		$this->defaultPluginOptions['checkboxClass'] = 'icheckbox_' . $this->skin;
		$this->defaultPluginOptions['radioClass'] = 'iradio_' . $this->skin;
		iCheckAsset::register($view)->skin($this->skin);
	}

	/**
	 * @param $selector
	 * @param $options
     */
	public function callPlugin($selector, $options)
	{
		$this->getView()->registerJs('$("' . $selector . ' input").iCheck(' . Json::encode($options) . ');');
	}
}
