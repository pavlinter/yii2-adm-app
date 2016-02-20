<?php

namespace app\modules\admunderconst;

use Yii;
use pavlinter\adm\AdmBootstrapInterface;
use yii\base\View;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package app\modules\admunderconst */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $settingsKey = 'admunderconst';

    public $controllerNamespace = 'app\modules\admunderconst\controllers';

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
                    'class' => 'app\modules\admunderconst\ModelManager'
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
                'key' => 'admunderconst',
                'label' => '<span>' . self::t('', 'Under Construction') . '</span>',
                'url' => ['/admunderconst']
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Yii::$app->getModule('adm');
        $adm->params['left-menu-active'][] = 'admunderconst';
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admunderconst*'])) {
            Yii::$app->i18n->translations['admunderconst*'] = [
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
            $category = 'admunderconst/' . $category;
        } else {
            $category = 'admunderconst';
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
            'SourceMessageSearch[category]' => 'admunderconst'
        ],], $options);
    }



    /**
     * @param \yii\web\View $view
     */
    public static function loadUnderConstruction($view)
    {
        $view = Yii::$app->getView();
        if (isset(Yii::$app->params['admunderconst'])) {
            $settings = Yii::$app->params['admunderconst'];
            if (isset($settings['active']) && $settings['active']) {
                if (Yii::$app->user->can('AdmRoot') || Yii::$app->user->can('AdmAdmin')) {
                    $view->on(\yii\web\View::EVENT_BEGIN_BODY, function ($event) use($view, $settings) {
                        echo $view->render('@webroot/modules/admunderconst/views/default/_underconstruction_alert', [
                            'settings' => $settings,
                        ]);
                    });
                } else {
                    Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function ($event) use($view, $settings){
                        /* @var $response \yii\web\Response */
                        $response = $event->sender;
                        $response->clearOutputBuffers();
                        $response->setStatusCode(200);
                        $response->format = \yii\web\Response::FORMAT_RAW;
                        $response->data = $view->renderFile('@webroot/modules/admunderconst/views/default/underconstruction.php',[
                            'settings' => $settings,
                        ]);
                        $response->off(\yii\web\Response::EVENT_BEFORE_SEND);
                        $response->send();
                        Yii::$app->end();
                    });
                }
            }
        }
    }

}
