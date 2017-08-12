<?php

namespace app\widgets;

use app\assets_b\SocialLikesAsset;
use app\assets_b\WhatsappAsset;
use app\helpers\ArrayHelper;
use app\helpers\Url;
use Yii;
use yii\helpers\Html;


/**
 * Class Likes
 * @link https://uptolike.com
 */
class Likes extends \yii\base\Widget
{
    public $options = [];

    public $data = [];

    public $dataDefault = [
        'background-alpha' => '0.0',
        'buttons-color' => '#f59c1a',
        'counter-background-color' => '#ffffff',
        'share-counter-size' => '12',
        'top-button' => 'false',
        'share-counter-type' => 'disable',
        'share-style' => '0',
        'mode' => 'share',
        'like-text-enable' => 'false',
        'hover-effect' => 'scale',
        'mobile-view' => 'true',
        'icon-color' => '#ffffff',
        'orientation' => 'horizontal',
        'text-color' => '#000000',
        'share-shape' => 'rectangle',
        'sn-ids' => 'fb.vk.tw.ok.gp.',
        'share-size' => '30',
        'background-color' => '#ffffff',
        'preview-mobile' => 'false',
        'mobile-sn-ids' => 'fb.vk.tw.wh.ok.vb.',
        'pid' => '1630299',
        'counter-background-alpha' => '1.0',
        'following-enable' => 'false',
        'exclude-show-more' => 'false',
        'selection-enable' => 'false',
    ];

    public function init()
    {
        parent::init();

        $data = ArrayHelper::remove($this->options, 'data', []);
        $this->options['data'] = ArrayHelper::merge($data, $this->dataDefault, $this->data);
        SocialLikesAsset::register($this->getView());

        Html::addCssClass($this->options, 'uptolike-buttons');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::tag('div', null, $this->options);
    }



}
