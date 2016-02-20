<?php

namespace app\modules\profilelogin;

use pavlinter\adm\Adm;
use pavlinter\adm\AdmBootstrapInterface;
use Yii;

/**
 * Class Module
 */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $controllerNamespace = 'app\modules\profilelogin\controllers';

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
