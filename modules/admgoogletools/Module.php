<?php

namespace app\modules\admgoogletools;

use Yii;
use pavlinter\adm\AdmBootstrapInterface;
use yii\base\View;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package app\modules\admgoogletools */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $settingsKey = 'admgoogletools';

    public $controllerNamespace = 'app\modules\admgoogletools\controllers';

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
                    'class' => 'app\modules\admgoogletools\ModelManager'
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
                'key' => 'admgoogletools',
                'label' => '<span>' . self::t('', 'Google') . '</span>',
                'url' => ['/admgoogletools']
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Yii::$app->getModule('adm');
        $adm->params['left-menu-active'][] = 'admgoogletools';
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admgoogletools*'])) {
            Yii::$app->i18n->translations['admgoogletools*'] = [
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
            $category = 'admgoogletools/' . $category;
        } else {
            $category = 'admgoogletools';
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
            'SourceMessageSearch[category]' => 'admgoogletools'
        ],], $options);
    }

    public static function loadGoogleTools()
    {
        $view = Yii::$app->getView();
        if (isset(Yii::$app->params['admgoogletools'])) {
            $settings = Yii::$app->params['admgoogletools'];
            if (isset($settings['active']) && $settings['active']) {
                if (isset($settings['webtools']) && $settings['webtools']) {
                    $view->metaTags[] = $settings['webtools'];
                }
                if (isset($settings['analytic']) && $settings['analytic']) {
                    $view->registerJs($settings['analytic'], $view::POS_HEAD);
                }
            }
        }


    }

}
