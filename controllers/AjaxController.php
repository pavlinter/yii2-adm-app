<?php

namespace app\controllers;

use app\core\admpages\models\Page;
use app\helpers\SiteHelper;
use app\models\form\ContactForm;
use app\models\form\FoundErrorForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Ajax controller
 */
class AjaxController extends Controller
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionCommon()
    {
        $json = [];

        $json['userOnline'] = SiteHelper::userOnline();

        return Json::encode($json);
    }
}
