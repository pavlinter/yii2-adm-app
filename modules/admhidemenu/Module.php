<?php

namespace app\modules\admhidemenu;

use pavlinter\adm\Adm;
use Yii;
use pavlinter\adm\AdmBootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package app\modules\admhidemenu */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $settingsKey = 'admhidemenu';

    public $controllerNamespace = 'app\modules\admhidemenu\controllers';

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    public $leftMenu = [];
    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();
        $config = ArrayHelper::merge([
            'components' => [
                'manager' => [
                    'class' => 'app\modules\admhidemenu\ModelManager'
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
        if (Yii::$app->user->can('AdmRoot')) {
            $adm->on($adm::EVENT_TOP_MENU, function ($event) {
                echo \app\widgets\Menu::widget([
                    'options' => ['class' => 'nav navbar-nav navbar-left'],
                    'items' => [
                        [
                            'key' => 'admhidemenu',
                            'label' => '<span>' . self::t('', 'Root Tools') . '</span>',
                            'url' => ['/admhidemenu/default/index']
                        ]
                    ],
                    'encodeLabels' => false,
                ]);
            });
        }

        $adm->on($adm::EVENT_INIT_LEFT_MENU, function ($event)  {
            $adm = $event->sender;
            $this->leftMenu = $adm->params['left-menu'];
            $this->checkMenu($adm);
        });
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Adm::register(); //required load adm,if use adm layout
        $adm->params['left-menu-active'][] = 'admhidemenu';
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admhidemenu*'])) {
            Yii::$app->i18n->translations['admhidemenu*'] = [
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
            $category = 'admhidemenu/' . $category;
        } else {
            $category = 'admhidemenu';
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
            'SourceMessageSearch[category]' => 'admhidemenu'
        ],], $options);
    }

    /**
     * @param $adm
     */
    public function checkMenu($adm)
    {
        /* @var $model \app\modules\admhidemenu\models\SettingsForm */
        $model = $this->manager->createSettingsForm();

        if (is_array($model->items)) {

            $items = $model->items;
            $leftMenuItems = $this->leftMenu;

            foreach ($leftMenuItems as $key => $item) {
                if(isset($items[$key])){
                    if (!$items[$key]) {
                        unset($leftMenuItems[$key]);
                    } elseif (isset($leftMenuItems[$key]['items'])){

                        foreach ($leftMenuItems[$key]['items'] as $k => $child) {
                            if(isset($child['key'])){
                                if(isset($items[$child['key']])){
                                    if (!$items[$child['key']]) {
                                        unset($leftMenuItems[$key]['items'][$k]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $adm->params['left-menu'] = $leftMenuItems;

        }
    }
}
