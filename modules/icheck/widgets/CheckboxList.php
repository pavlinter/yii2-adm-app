<?php

namespace app\modules\icheck\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class CheckboxList
 * $form->field($model, 'active', [
 *  'template' => "{input}",
 * ])->widget(\app\modules\icheck\widgets\CheckboxList::className(), [
 *  'skin' => \app\modules\icheck\widgets\CheckboxList::SKIN_MINIMAL_BLUE,
 *  'items' => ['value1' => 'One', 'value2' => 'Two'],
 * ])
 */
class CheckboxList extends iCheckOptions
{
	public $items = [];

	public $template = '{input}';

	public $containerOptions = [];

	public $pluginOptions = [];

	/**
	 *   var line: callable, a callback that can be used to customize the generation of the HTML code
	 *   corresponding to a single item in $items. The signature of this callback must be:
	 *
	 *   ```php
	 *   function ($index, $label, $name, $checked, $value)
	 *   ```
	 *
	 *   where $index is the zero-based index of the radio button in the whole list; $label
	 *   is the label for the radio button; and $name, $value and $checked represent the name,
	 *   value and the checked status of the radio button input, respectively.
	 *
	 * See [[renderTagAttributes()]] for details on how attributes are being rendered.
	 */
	public $line;

	public function init()
	{
		parent::init();

		if (!isset($this->containerOptions['id'])) {
			$this->containerOptions['id'] = $this->getId();
		}
		if ($this->line) {
			$this->options['item'] = $this->line;
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
			$list = Html::activeCheckboxList($this->model, $this->attribute, $this->items, $this->options);
		} else {
			$list = Html::checkboxList($this->name, $this->value, $this->items, $this->options);
		}


		$input = Html::tag('div', $list, $this->containerOptions);

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
