<?php

namespace app\modules\slidebars\widgets;

use app\modules\slidebars\SlidebarsAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;


/**
 * Class Slidebars
 * @package app\widgets
 * @link https://www.adchsm.com/slidebars/
 *
 * example:
 * <?php Slidebars::begin([
 * 	'toggleSelector' => ".slidebars-btn",
 * 	'menuContent' => function ($widget) {
 * 	?>
 * 	<div class="slidebar-1">
 * 		<a href="#" class="slidebars-btn slidebar-close"><span class="fa fa-times-circle"></span></a>
 * 		<img class="logo-menu" src="https://www.adchsm.com/slidebars/images/slidebars-logo-white.png" alt="logo">
 * 		<ul class="menu">
 * 			<li><a href="#"><span class="fa fa-home"></span> Home</a></li>
 * 			<li><a href="#"><span class="fa fa-check-circle"></span> Features</a></li>
 * 			<li><a href="#"><span class="fa fa-cloud-download"></span> Downloads</a></li>
 * 			<li><a href="#" class="js-toggle-demos-menu"><span class="fa fa-eye"></span> Demos</a></li>
 * 			<li><a href="#" class="js-toggle-help-menu"><span class="fa fa-life-ring"></span> Help Center</a></li>
 * 			<li><a href="#"><span class="fa fa-info-circle"></span> Compatibility</a></li>
 * 			<li><a href="#" class="ga-external-github"><span class="fa fa-github"></span> Slidebars on GitHub</a></li>
 * 			<li><a href="#" class="js-toggle-author-menu"><span class="fa fa-user"></span> About the Author</a></li>
 * 			<li><a href="#" class="js-close-any"><span class="fa fa-times-circle"></span> Close</a></li>
 * 		</ul>
 * 	</div>
 * 	<?php
 * 	}
 * ]); ?>
 * <a href="javascript:void(0);" class="btn btn-primary slidebars-btn">Open</a>
 * content...
 * <?php Slidebars::end() ?>
 *
 */
class Slidebars extends Widget
{
	const SIDE_TOP = 'top';
	const SIDE_RIGHT = 'right';
	const SIDE_BOTTOM = 'bottom';
	const SIDE_LEFT = 'left';

	const STYLE_REVEAL = 'reveal';
	const STYLE_PUSH = 'push';
	const STYLE_OVERLAY = 'overlay';
	const STYLE_SHIFT = 'shift';

	public $id;

	public $side = self::SIDE_LEFT;

	public $style = self::STYLE_OVERLAY;

	public $options = [];

	public $menuOptions = [];
	/**
	 * @var string|callback
	 */
	public $menuContent;

	public $toggleSelector;

    /**
     * @var array widget JQuery events. You must define events in `event-name => event-function` format. For example:
     *
     * ~~~
     * pluginEvents = [
     *     'init' => 'function() {}',
     *     'opening' => 'function() {}',
     * ];
     * ~~~
     */
    public $pluginEvents = [];


	public function init()
	{
		parent::init();

		if ($this->id === null) {
			$this->id = $this->getId();
		}

		$options = ArrayHelper::merge([
			'canvas' => 'container',
            'id' => $this->id,
		], $this->options);

		if ($this->menuContent instanceof \Closure) {
			ob_start();
			ob_implicit_flush(false);
			echo call_user_func($this->menuContent, $this);
			$this->menuContent = ob_get_clean();
		}

		$this->menuOptions['off-canvas'] = $this->id . ' ' . $this->side . ' ' . $this->style;
        if (!isset($this->menuOptions['id'])) {
            $this->menuOptions['id'] = 'menu_' . $this->id;
        }
		echo Html::tag('div', $this->menuContent, $this->menuOptions);
		echo Html::beginTag('div', $options) . "\n";
	}

	/**
	 *
	 */
	public function run()
	{
		echo Html::endTag('div');
		$view = $this->getView();
		$this->registerScript($view);
	}

	/**
	 * @param $view \yii\web\View
	 */
	public function registerScript($view)
	{
		SlidebarsAsset::register($view);
		$varName = $this->varName();

        $js[] = "var {$varName} = new slidebars();";

        foreach ($this->pluginEvents as $event => $handler) {
            $function = new JsExpression($handler);
            $js[] = "$({$varName}.events).on('{$event}', {$function});";
        }

		if ($this->toggleSelector) {
            $js[] = "$('{$this->toggleSelector}').on('click', function(e){e.stopPropagation();e.preventDefault();{$varName}.toggle('{$this->id}');});";
		}
        $js[] = "{$varName}.init();";
        $js = implode("\n", $js);
        $view->registerJs($js);
	}

    /**
     * @return string
     */
    public function varName()
    {
        return 'slidebars_'. $this->id;
    }
}
