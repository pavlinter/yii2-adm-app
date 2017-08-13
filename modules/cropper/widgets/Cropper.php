<?php

namespace app\modules\cropper\widgets;

use app\modules\cropper\CropperAvatarAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class Cropper
 * @link https://github.com/fengyuanchen/cropper/
 * @package app\modules\cropper\widgets
 */
class Cropper extends \yii\base\Widget
{
    /** @var string URL for send crop data */
    public $cropUrl = ['/user/cropper/avatar'];
    /** @var string default image URL */
    public $image;

    public $avatarView = '@webroot/modules/cropper/views/partial/avatar';

    public $options = [];

    public $imageOptions = [];

    public $avatarViewOptions = [];

    public $formConfig = [];

    public $pluginOptions = [];

    public $pluginCallbacks = [];

    public $pluginEvents = [];

    public $popupConfig = [];

    public $defaultPluginOptions = [
        'viewMode' => 1,
        'dragMode' => 'move',
        'autoCropArea' => 1,
        'restore' => false,
        'guides' => false,
        'highlight' => false,
        'cropBoxMovable' => false,
        'cropBoxResizable' => false,
        'aspectRatio' => 1,
    ];

    public $defaultPluginCallbacks = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        } else {
            $this->setId($this->options['id']);
        }

        $this->pluginOptions = ArrayHelper::merge($this->defaultPluginOptions, $this->pluginOptions);
        $this->pluginCallbacks = ArrayHelper::merge($this->defaultPluginCallbacks, $this->pluginCallbacks);

        $this->popupConfig = ArrayHelper::merge([
            'toggleButton' => [
                'id' => $this->prefix('avatar-modal-btn'),
                'class' => 'avatar-modal-btn-hide',
            ],
            'popupClass' => 'avatar-popup',
            'popupOptions' => [],
            'pluginOptions' => [
                'closeOnBgClick' => true,
                'showCloseBtn' => true,
            ],
            'effect' => 'zoom-in',
        ], $this->popupConfig);

        foreach ($this->pluginCallbacks as $key => $value) {
            $this->pluginOptions[$key] = new JsExpression($value);
        }

        if (!isset($this->avatarViewOptions['title'])) {
            $this->avatarViewOptions['title'] = Yii::t("app/cropper", "Change the avatar", ['dot' => false]);
        }
        Html::addCssClass($this->avatarViewOptions, 'avatar-view');

        $this->formConfig = ArrayHelper::merge([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
            'action' => $this->cropUrl,
        ], $this->formConfig);

        Html::addCssClass($this->formConfig['options'], 'avatar-form');

        $this->registerScript();
        return $this->render($this->avatarView, ['widget' => $this]);
    }

    /**
     * Registers required script for the plugin to work as jQuery image cropping
     */
    public function registerScript()
    {
        $view = $this->getView();
        CropperAvatarAsset::register($view);

        $settings = [
            'cropOptions' => $this->pluginOptions,
            'cropEvents' => $this->pluginEvents,
            'popupSelector' => '#' . $this->popupConfig['toggleButton']['id'],
        ];
        $view->registerJs('new CropAvatar($("#' . $this->id . '"), ' . Json::encode($settings) . ');');
    }

    /**
     * @param $name
     * @return string
     */
    public function prefix($name)
    {
        return $this->id . '-' . $name;
    }
}