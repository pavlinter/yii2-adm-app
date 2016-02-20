<?php

namespace app\modules\appadm;

use pavlinter\adm\Adm;
use pavlinter\adm\AdmBootstrapInterface;
use Yii;

/**
 * Class Module
 */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $controllerNamespace = 'app\modules\appadm\controllers';

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
    /**
     * @inheritdoc
     */
    public function loading($adm)
    {
        $adm->params['left-menu']['appadm-dashboard'] = [
            'label' => '<i class="fa fa-list-alt"></i><span>' . $adm::t('appadm','Dashboard') . '</span>',
            'url' => ['/appadm/dashboard/index'],
            'visible' => $adm->user->can('AdmRoot') || $adm->user->can('AdmAdmin'),
        ];

        $adm->params['left-menu']['appadm-contact-msg'] = [
            'label' => '<i class="fa fa-envelope-square"></i><span>' . $adm::t('appadm','Contact') . '</span>',
            'url' => ['/appadm/contact-msg/index'],
            'visible' => $adm->user->can('AdmRoot') || $adm->user->can('AdmAdmin'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $adm = Adm::register(); //required load adm,if use adm layout
        /*if (!parent::beforeAction($action) || !$adm->user->can('AdmRoot')) {
            throw new ForbiddenHttpException('Access denied');
            return false;
        }*/
        return parent::beforeAction($action);
    }
}
