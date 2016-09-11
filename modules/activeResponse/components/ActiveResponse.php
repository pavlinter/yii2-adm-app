<?php

namespace app\modules\activeResponse\components;

use Yii;
use yii\base\Component;
use yii\base\Model;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;


/**
 * Yii2 ajax module with active control from server side
 * @see http://github.com/srusakov/yii2-activeresponse
 * @since 1.0
 */
class ActiveResponse extends Component {
    /**
     * @var array Массив действий для исполнения на стороне клиента
     */
    public $result = [
        'actions' => [], // массив действий, которые надо будет выполнить на стороне клиента
        'disableActions' => false,
        'return2callback' => null, // значение, которое можно возвратить в callback функцию, указанную при $.callPHP
    ];

    /**
     * @return string
     */
    public function __toString() {
        return Json::encode($this->result);
    }


    /**
     * $.notify(options);
     * @param $msg
     * @param array $options
     * @param array $settings
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function growl($msg, $options = [], $settings = [], $condition = false)
    {
        $options = ArrayHelper::merge([
            'message' => $msg,
        ], $options);

        $settings = ArrayHelper::merge([
            'delay' => 10000,
        ], $settings);

        $this->addAction('notify', ['options' => $options, 'settings' => $settings, 'condition' => $condition]);
        return $this;
    }

    /**
     * $.notify(options);
     * @param $msg
     * @param array $options
     * @param array $settings
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function growlSuccess($msg, $options = [], $settings = [], $condition = false)
    {
        $settings = ArrayHelper::merge([
            'type' => 'success',
        ], $settings);
        return $this->growl($msg, $options, $settings, $condition);
    }

    /**
     * $.notify(options);
     * @param $msg
     * @param array $options
     * @param array $settings
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function growlInfo($msg, $options = [], $settings = [], $condition = false)
    {
        $settings = ArrayHelper::merge([
            'type' => 'info',
        ], $settings);
        return $this->growl($msg, $options, $settings, $condition);
    }

    /**
     * $.notify(options);
     * @param $msg
     * @param array $options
     * @param array $settings
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function growlError($msg, $options = [], $settings = [], $condition = false)
    {
        $settings = ArrayHelper::merge([
            'type' => 'danger',
        ], $settings);
        return $this->growl($msg, $options, $settings, $condition);
    }

    /**
     * $.notify(options);
     * @param $msg
     * @param array $options
     * @param array $settings
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function growlWarning($msg, $options = [], $settings = [], $condition = false)
    {
        $settings = ArrayHelper::merge([
            'type' => 'warning',
        ], $settings);
        return $this->growl($msg, $options, $settings, $condition);
    }


    /**
     * jQuery("#form").yiiActiveForm('updateMessages', errors, summary)
     * @param \yii\base\Model $model
     * @param $selectorForm
     * @param string|bool $condition with eval();
     * @param bool $summary
     * @return $this
     */
    public function formUpdateMessages($model, $selectorForm, $condition = false, $summary = true)
    {
        $errors = static::formValidate($model);
        $this->addAction('formUpdateMessages', ['form' => $selectorForm, 'errors' => $errors, 'summary' => $summary, 'condition' => $condition]);
        return $this;
    }

    /**
     * similar ActiveForm::validate($model, $attributes = null)
     * @param $model
     * @param null $attributes
     * @param bool $clearErrors
     * @return array
     */
    public static function formValidate($model, $attributes = null, $clearErrors = false)
    {
        $result = [];
        if ($attributes instanceof Model) {
            // validating multiple models
            $models = func_get_args();
            $attributes = null;
        } else {
            $models = [$model];
        }
        /* @var $model Model */
        foreach ($models as $model) {
            $model->validate($attributes, $clearErrors);
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }
        }
        return $result;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function disableActions($bool = true)
    {
        $this->result['disableActions'] = $bool;
        return $this;
    }


    /**
     * @param string $selector
     * @param string $msg
     * @param string $type info|success|warning|danger
     * @param array $widgetSettings Alert::widget()
     * @param string $method
     * @param bool $condition
     * @return $this
     */
    public function bsAlert($selector, $msg, $type = 'info', $widgetSettings = [], $method = 'html', $condition = false)
    {
        if ($this->hasMethod($method)) {
            $widgetSettings = ArrayHelper::merge([
                'options' => [
                    'class' => 'alert-' . $type,
                ],
                'body' => $msg,
            ], $widgetSettings);
            $this->$method($selector, Alert::widget($widgetSettings), $condition);
        }
        return $this;
    }

    /**
     * Alert a message with window.alert(msg)
     * @param $msg
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function alert($msg, $condition = false)
    {
        $this->addAction('alert', ['msg' => $msg, 'condition' => $condition]);
        return $this;
    }

    /**
     * jQuery(selector).method(val);
     * @param string $method html|append|prepend|val|after|before
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function method($method, $selector, $val, $condition = false)
    {
        $this->addAction('method', ['selector' => $selector, 'val' => $val, 'method' => $method, 'condition' => $condition]);
        return $this;
    }

    /**
     * jQuery(selector).method(val);
     * @param string $method html|append|prepend|val|after|before
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function methodLoad($method, $selector, $val, $condition = false)
    {
        $this->addAction('methodLoad', ['selector' => $selector, 'val' => $val, 'method' => $method, 'condition' => $condition]);
        return $this;
    }

    /**
     * jQuery(selector).val(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function val($selector, $val, $condition = false)
    {
        $this->method('val', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).html(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function html($selector, $val, $condition = false)
    {
        $this->method('html', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).append(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function append($selector, $val, $condition = false)
    {
        $this->method('append', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).prepend(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function prepend($selector, $val, $condition = false)
    {
        $this->method('prepend', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).after(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function after($selector, $val, $condition = false)
    {
        $this->method('after', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).before(val);
     * @param $selector
     * @param $val
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function before($selector, $val, $condition = false)
    {
        $this->method('after', $selector, $val, $condition);
        return $this;
    }

    /**
     * jQuery(selector).attr(props);
     * @param $selector
     * @param $props array
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function attr($selector, $props, $condition = false)
    {
        $this->method('attr', $selector, $props, $condition);
        return $this;
    }

    /**
     * jQuery(selector).css(props);
     * @param $selector
     * @param $props array
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function css($selector, $props, $condition = false)
    {
        $this->method('css', $selector, $props, $condition);
        return $this;
    }


    /**
     * location.href = href;
     * @param $href
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function redirect($href, $condition = false)
    {
        $this->addAction('redirect', ['href' => $href, 'condition' => $condition]);
        return $this;
    }


    /**
     * execute javascript with eval();
     * @param $script
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function script($script, $condition = false)
    {
        $this->addAction('script', ['script' => $script, 'condition' => $condition]);
        return $this;
    }


    /**
     * jQuery(selector).focus()
     * @param $selector
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function focus($selector, $condition = false)
    {
        $this->script("\$('{$selector}').focus();", $condition);
        return $this;
    }

    /**
     * jQuery(selector).addClass(class);
     * @param $selector
     * @param $class
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function addClass($selector, $class, $condition = false)
    {
        $this->script("\$('{$selector}').addClass('{$class}');", $condition);
        return $this;
    }


    /**
     * jQuery(selector).removeClass(class);
     * @param $selector
     * @param $class
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function removeClass($selector, $class, $condition = false)
    {
        $this->script("\$('{$selector}').removeClass('{$class}');", $condition);
        return $this;
    }


    /**
     * jQuery(selector).show(item);
     * @param $selector
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function show($selector, $condition = false)
    {
        $this->script("\$('{$selector}').show();", $condition);
        return $this;
    }


    /**
     * jQuery(selector).hide(item);
     * @param $selector
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function hide($selector, $condition = false)
    {
        $this->script("\$('{$selector}').hide();", $condition);
        return $this;
    }


    /**
     * jQuery(selector).fadeIn(item);
     * @param $selector
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function fadeIn($selector, $condition = false)
    {
        $this->script("\$('{$selector}').fadeIn();", $condition);
        return $this;
    }

    /**
     * jQuery(selector).fadeOut(item);
     * @param $selector
     * @param string|bool $condition with eval();
     * @return $this
     */
    public function fadeOut($selector, $condition = false)
    {
        $this->script("\$('{$selector}').fadeOut();", $condition);
        return $this;
    }

    /**
     * @param $name
     * @param array $data
     */
    public function addAction($name, $data = [])
    {
        $data = ArrayHelper::merge([
            'act' => $name,
            'condition' => false,
        ], $data);

        $this->result['actions'][] = $data;
    }
}
