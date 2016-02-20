<?php

namespace app\modules\admlivechat;

use Yii;
use pavlinter\adm\AdmBootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package app\modules\admlivechat */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $settingsKey = 'admlivechat';

    public $controllerNamespace = 'app\modules\admlivechat\controllers';

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();
        $config = ArrayHelper::merge([
            'components' => [
                'manager' => [
                    'class' => 'app\modules\admlivechat\ModelManager'
                ],
            ],
        ], $config);

        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @param \pavlinter\adm\Adm $adm
     */
    public function loading($adm)
    {
        if (Yii::$app->user->can('AdmRoot') || Yii::$app->user->can('AdmAdmin')) {

            if (!isset($adm->params['left-menu']['api'])) {
                $adm->params['left-menu']['api'] = [
                    'label' => '<span class="pull-right auto"><i class="fa fa-angle-down text"></i><i class="fa fa-angle-up text-active"></i></span><i class="fa fa-th"></i><span>' . $adm::t("menu", "API") . '</span>',
                    'url' => "#",
                    'items' => [],
                ];
            }
            $adm->params['left-menu']['api']['items'][] = [
                'key' => 'admlivechat',
                'label' => '<span>' . self::t('', 'Live Chat') . '</span>',
                'url' => ['/admlivechat']
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Yii::$app->getModule('adm');
        $adm->params['left-menu-active'][] = 'admlivechat';
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admlivechat*'])) {
            Yii::$app->i18n->translations['admlivechat*'] = [
                'class' => 'pavlinter\translation\DbMessageSource',
                'forceTranslation' => true,
                'autoInsert' => true,
                'dotMode' => true,
            ];
        }
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if ($category) {
            $category = 'admlivechat/' . $category;
        } else {
            $category = 'admlivechat';
        }
        return Yii::t($category, $message, $params, $language);
    }

    /**
     * @param array $options
     * @return string
     */
    public static function trasnalateLink($options = [])
    {
        $icon = ArrayHelper::remove($options, 'icon', 'glyphicon glyphicon-globe');

        if(!isset($options['class'])) {
            $options['class'] = 'pull-right';
        }
        if(!isset($options['target'])) {
            $options['target'] = '_blank';
        }
        \yii\helpers\Html::addCssClass($options, $icon);

        return \yii\helpers\Html::a(null, ['/adm/source-message/index', '?' => [
            'SourceMessageSearch[category]' => 'admlivechat'
        ],], $options);
    }

    public static function loadLiveChat()
    {
        $view = Yii::$app->getView();
        if (isset(Yii::$app->params['admlivechat'])) {
            $settings = Yii::$app->params['admlivechat'];
            if (isset($settings['active']) && $settings['active']) {
                $language_id = Yii::$app->getI18n()->language['id'];
                if (isset($settings['scripts'][$language_id]) && $settings['scripts']) {
                    if ($settings['scripts']) {
                        $view->on($view::EVENT_BEGIN_BODY, function ($e) use ($settings, $language_id) {
                            echo $settings['scripts'][$language_id];
                        });
                    }
                }
            }
        }
    }
}
