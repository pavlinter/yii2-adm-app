<?php

namespace app\modules\icheck\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Checkbox
 */
class Checkbox extends iCheckOptions
{
	public $template = '{input}';

	public $containerOptions = [];

	public $pluginOptions = [];

	public $label;

	public $labelOptions = [];

	public function init()
	{
		parent::init();

		if (!isset($this->containerOptions['id'])) {
			$this->containerOptions['id'] = $this->getId();
		}

		if ($this->label && !isset($this->labelOptions['for'])) {
		    $this->labelOptions['for'] = $this->options['id'];
		}
	}

	/**
	 *
	 */
	public function run()
	{
		$view = $this->getView();
		$this->registerScript($view);

		if ($this->hasModel()) {
			$checkbox = Html::activeCheckbox($this->model, $this->attribute, $this->options);
		} else {
			$checkbox = Html::checkbox($this->name, $this->value, $this->options);
			if ($this->label) {
				$checkbox = Html::tag('label', $checkbox . ' ' . $this->label, $this->labelOptions);
			}
		}

		$input = Html::tag('div', $checkbox, $this->containerOptions);

		echo strtr($this->template, [
			'{input}' => $input,
		]);
	}

	/**
	 * @param $view \yii\web\View
	 */
	public function registerScript($view)
	{
		$this->setSkin();
		$options = ArrayHelper::merge($this->defaultPluginOptions, $this->pluginOptions);
		$this->callPlugin("#" . $this->containerOptions['id'], $options);
	}
}
