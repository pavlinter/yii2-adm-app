<?php

namespace app\modules\admevents;

use pavlinter\adm\Adm;
use Yii;
use pavlinter\adm\AdmBootstrapInterface;
use yii\base\View;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package app\modules\admevents */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $settingsKey = 'admevents';

    public $controllerNamespace = 'app\modules\admevents\controllers';

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
                    'class' => 'app\modules\admevents\ModelManager'
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
                'key' => 'admevents',
                'label' => '<span>' . self::t('', 'Events') . '</span>',
                'url' => ['/admevents']
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Adm::register(); //required load adm,if use adm layout
        $adm->params['left-menu-active'][] = 'admevents';
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admevents*'])) {
            Yii::$app->i18n->translations['admevents*'] = [
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
            $category = 'admevents/' . $category;
        } else {
            $category = 'admevents';
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
            'SourceMessageSearch[category]' => 'admevents'
        ],], $options);
    }

    /**
     * @return bool
     */
    public static function loadEvents()
    {
        $view = Yii::$app->getView();
        if (isset(Yii::$app->params['admevents'])) {
            $settings = Yii::$app->params['admevents'];
            if (isset($settings['active']) && $settings['active']) {
                if (isset($settings['head']) && $settings['head']) {
                    $view->on('head', function ($event) use ($settings){
                        echo $settings['head'];
                    });
                }
                if (isset($settings['beginBody']) && $settings['beginBody']) {
                    $view->on($view::EVENT_BEGIN_BODY, function ($event) use ($settings){
                        echo $settings['beginBody'];
                    });
                }
                if (isset($settings['endBody']) && $settings['endBody']) {
                    $view->on($view::EVENT_END_BODY, function ($event) use ($settings){
                        echo $settings['endBody'];
                    });
                }
            }
        }
    }

}
