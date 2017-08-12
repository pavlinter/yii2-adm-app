<?php

namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\validators\RequiredValidator;

/**
 * Class ActiveField
 * @package app\widgets
 */
class ActiveField extends \yii\widgets\ActiveField
{
    const TOOLTIP_THEME_LIGHT = 'tooltip-light';

    public $requiredSymbol = '&nbsp;<span class="required-symbol">*</span>';

    public $tooltipMsg;

    public $tooltipData = [];

    public $tooltipPlacement = 'auto-right';


    public $tooltipTheme = self::TOOLTIP_THEME_LIGHT;
    /**
     * @var bool if "for" field label attribute should be skipped.
     */
    private $_skipLabelFor = false;

    public $template = "{label}\n{input}\n{hint}\n{error}";

    public $hintOptions = ['class' => 'my-tooltip-note'];


    public function init()
    {
        parent::init();
        if (Yii::$app->mobileDetect->isMobile()) {
            $this->tooltipPlacement = 'auto-top';
        }
    }

    /**
     * Renders a text input.
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute
     * unless they are explicitly specified in `$options`.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[Html::encode()]].
     *
     * The following special options are recognized:
     *
     * - `maxlength`: integer|boolean, when `maxlength` is set `true` and the model attribute is validated
     *   by a string validator, the `maxlength` option will take the value of [[\yii\validators\StringValidator::max]].
     *   This is available since version 2.0.3.
     *
     * Note that if you set a custom `id` for the input element, you may need to adjust the value of [[selectors]] accordingly.
     *
     * @return $this the field object itself.
     */
    public function textInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        if ($this->tooltipMsg) {
            $this->hint($this->tooltipMsg);
            $options = array_merge([
                'data-placement' => $this->tooltipPlacement,

            ],$options, $this->tooltipData);
            $options['data-content'] = $this->tooltipMsg;
            Html::addCssClass($options, 'my-tooltip');
            //Html::addCssClass($options, $this->tooltipTheme);
        }

        $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Renders a text area.
     * The model attribute value will be used as the content in the textarea.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[Html::encode()]].
     *
     * If you set a custom `id` for the textarea element, you may need to adjust the [[$selectors]] accordingly.
     *
     * @return $this the field object itself.
     */
    public function textarea($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        if ($this->tooltipMsg) {
            $options = array_merge([
                'data-placement' => $this->tooltipPlacement,
            ],$options, $this->tooltipData);
            $options['data-content'] = $this->tooltipMsg;
            Html::addCssClass($options, 'my-tooltip');
        }
        $this->parts['{input}'] = Html::activeTextarea($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Generates a label tag for [[attribute]].
     * @param null|string|false $label the label to use. If `null`, the label will be generated via [[Model::getAttributeLabel()]].
     * If `false`, the generated field will not contain the label part.
     * Note that this will NOT be [[Html::encode()|encoded]].
     * @param null|array $options the tag options in terms of name-value pairs. It will be merged with [[labelOptions]].
     * The options will be rendered as the attributes of the resulting tag. The values will be HTML-encoded
     * using [[Html::encode()]]. If a value is `null`, the corresponding attribute will not be rendered.
     * @return $this the field object itself.
     */
    public function label($label = null, $options = [])
    {
        if ($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }

        $options = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $options['label'] = $label;
        }

        if ($this->_skipLabelFor) {
            $options['for'] = null;
        }

        if ($this->isRequired()) {
            $options['label'] = $this->model->getAttributeLabel($this->attribute) . $this->requiredSymbol;
        }

        $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        if ($this->requiredSymbol) {
            foreach ($this->model->getActiveValidators($this->attribute) as $validator) {
                /* @var $validator \yii\validators\Validator */
                if ($validator instanceof RequiredValidator) {
                    return true;
                }
            }
        }
        return false;
    }
}
