<?php

namespace app\widgets;

use app\helpers\Url;
use app\modules\activeResponse\ActiveResponseAsset;
use app\modules\magnific\widgets\MagnificModal;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class AjaxConfirmButton
 * echo \app\widgets\AjaxConfirmButton::widget([
 *      'url' => Url::current(),
 *      'label' => "Remove",
 *      'content' => "sure?",
 *      'warningContent' => false,
 *      'ajaxOptions' => [
 *          'data' => [
 *              'id' => 1,
 *          ],
 *      ],
 *      ]);
 */
class AjaxConfirmButton extends Widget
{
    public $url;

    public $disabled = false;
    /**
     * @var boolean for simple post link without ajax
     */
    public $isPostLink = true;
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    public $content;

    public $warningContent;
    /**
     * @var string the button label
     */
    public $label = 'Button';

    public $template = '{content} {footer}';

    public $btnTemplate = '{btn-ok} {btn-close}';

    public $bgLeaderOptions = [];

    public $spinnerOptions = [];

    public $loadingClass = 'fa fa-spinner fa-spin'; //work if content not set

    public $contentOptions = [
        'class' => 'text-center',
    ];

    public $btnOkOptions = [
        'class' => 'btn btn-theme',
    ];

    public $btnCloseOptions = [
        'class' => 'btn btn-danger',
    ];

    public $ajaxOptions = [];
    /**
     * @var string confirm message
     */
    public $confirmMessage;

    public function init()
    {
        if ($this->url === null) {
            $this->url = Url::current();
        }

        $this->options = ArrayHelper::merge([
            'id' => $this->getId(),
            'tag' => $this->content === null ? 'button' : 'a',
            'class' => 'btn btn-primary',
            'disabled' => $this->disabled,
            'label' => $this->label,
        ], $this->options);

        if ($this->warningContent === null) {
            $this->warningContent = "<br/><br/>" . Html::tag('b', static::t('Back not return!'));
        }
        if ($this->content !== null) {
            $this->content .= $this->warningContent;
            MagnificModal::begin([
                'toggleButton' => $this->options,
                'popupClass' => 'confirm-popup',
                'popupOptions' => [
                    'id' => $this->prefix('popup'),
                ],
                'pluginOptions' => [
                    'closeOnBgClick' => false,
                    'showCloseBtn' => false,
                ],
                'effect' => 'zoom-out',
            ]);
        }
    }


    /**
     * Initializes the widget.
     */
    public function run()
    {

        if ($this->content === null) {
            $tag = ArrayHelper::remove($this->options, 'tag');
            $label = ArrayHelper::remove($this->options, 'label');
            if ($tag === 'button') {
                echo Html::button($label, $this->options);
            } else {
                echo Html::tag($tag, $label, $this->options);
            }

        } else {
            $tag = ArrayHelper::remove($this->contentOptions, 'tag', 'div');
            Html::addCssClass($this->bgLeaderOptions, 'bg-loader');
            Html::addCssClass($this->spinnerOptions, 'spinner-small');
            Html::addCssClass($this->contentOptions, 'confirm-body');
            echo Html::beginTag('div', $this->bgLeaderOptions);
            echo Html::tag('span', null, $this->spinnerOptions);
            echo Html::endTag('div');
            echo strtr($this->template, [
                '{content}' => Html::tag($tag, $this->content, $this->contentOptions),
                '{footer}' => $this->renderFooter(),
            ]);

            MagnificModal::end();
        }

        $this->registerScript();
    }

    /**
     * @return string
     */
    public function renderFooter()
    {
        $html = Html::beginTag('div', [
            'class' => 'confirm-footer',
        ]);

        if (!isset($this->btnOkOptions['id'])) {
            $this->btnOkOptions['id'] = $this->prefix('btn-ok');
        }

        if ($this->isPostLink && $this->ajaxOptions === false) {
            $this->btnOkOptions['data-method'] = 'post';
        }

        if (!isset($this->btnCloseOptions['id'])) {
            $this->btnCloseOptions['id'] = $this->prefix('btn-close');
        }

        Html::addCssClass($this->btnCloseOptions, 'mfp-close-action');

        $html .= strtr($this->btnTemplate, [
            '{btn-close}' => Html::a(static::t('No'), 'javascript:void(0);', $this->btnCloseOptions),
            '{btn-ok}' => Html::a(static::t('Yes'), 'javascript:void(0);', $this->btnOkOptions),
        ]);

        $html .= Html::endTag('div');

        return $html;
    }

    /**
     *
     */
    protected function registerScript()
    {
        $view = $this->getView();
        if ($this->content !== null) {
            $view->registerJs('$(document).on("click", ".mfp-close-action", function(e){$.magnificPopup.close();})', $view::POS_READY, static::className());
        }

        if ($this->ajaxOptions === false) {
            return null;
        }


        $this->ajaxOptions = ArrayHelper::merge([
            'url' => $this->url,
            'data' => [],
            'success' => '',
            'always' => '',
            'error' => '',
        ], $this->ajaxOptions);


        foreach (['success', 'always', 'error'] as $name) {
            $f = ArrayHelper::remove($this->ajaxOptions, $name);
            if (!($f instanceof JsExpression)) {
                $f = new JsExpression("function(){" . $f . "}");
            }
            $this->ajaxOptions[$name] = $f;
        }

        ActiveResponseAsset::register($view)->run();
        if ($this->content === null) {
            $view->registerJs('$("#' . $this->options['id'] . '").on("click", function(e){
            var o = ' . Json::encode($this->ajaxOptions) . ';
            var $btn =  $(this);
            var label = $btn.data("label");
            if(!label){
                label = $btn.text();
                $btn.data("label", label);
            }
            var $loading = $("<span>").addClass("' . $this->loadingClass . '");
            $btn.prop("disabled", true);
            $btn.width($btn.width());
            $btn.html($loading);
            activeResponse.callAR(o.url, o.data, {
                success: function(d){
                    $btn.html(label);
                    if(d.btnValue){
                        $btn.text(d.btnValue);
                    }
                    if(d.btnAttr){
                        $btn.attr(d.btnAttr);
                    }
                    if(d.disabled){
                        $btn.attr({disabled: true, href: "javascript:void(0);"});
                        $btn.off("click");
                    } else {
                        $btn.prop("disabled", false);
                    }
                    o.success(d);
                },
                always: function(jqXHR, textStatus){
                    o.success(jqXHR, textStatus);
                },
                error: function(jqXHR, textStatus, message){
                    $btn.prop("disabled", false);
                    $btn.html(label);
                    o.error(jqXHR, textStatus, message);
                },
            });
        })');
        } else {
            $view->registerJs('$("#' . $this->prefix('btn-ok') . '").on("click", function(e){
            var o = ' . Json::encode($this->ajaxOptions) . ';
            var $btn =  $("#' . $this->prefix() . '");
            var $btnClose =  $("#' . $this->prefix('btn-close') . '");
            var $btnOk =  $(this);
            var $popup =  $("#' . $this->prefix('popup') . '");
            var $loading = $popup.find(".bg-loader");
            $loading.show();
            activeResponse.callAR(o.url, o.data, {
                success: function(d){
                    if(d.btnValue){
                        $btn.text(d.btnValue);
                    }
                    if(d.btnAttr){
                        $btn.attr(d.btnAttr);
                    }
                    if(d.disabled){
                        $btn.attr({disabled: true, href: "javascript:void(0);"});
                        $btn.off("click");
                    }

                    if(d.closePopup){
                        $.magnificPopup.close();
                    }
                    o.success(d);
                },
                always: function(jqXHR, textStatus){
                    $loading.hide();
                    o.success(jqXHR, textStatus);
                },
                error: function(jqXHR, textStatus, message){
                    o.error(jqXHR, textStatus, message);
                },
            });
        })');
        }

    }


    /**
     * @param null $name
     * @return string
     */
    public function prefix($name = null)
    {
        if ($name === null) {
            return $this->options['id'];
        }

        return $this->options['id']. '-' . $name;
    }


    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        if (!isset($params['dot'])) {
            $params['dot'] = false;
        }
        return Yii::t("app", $message, $params);
    }
}
