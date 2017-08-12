<?php
/**
 * @copyright Copyright &copy; Pavels Radajevs, 2015
 * @package yii2-display-image2
 * @version 0.3.0
 */

namespace app\controllers;

use app\models\UserDisplay;
use pavlinter\display2\Module;
use Yii;

/**
 * Class DisplayController
 * @package app\controllers
 */
class DisplayController extends \pavlinter\display2\controllers\ImageController
{
    public function actionCrop()
    {
        $imageConfig = [
            'id_row' => Yii::$app->request->get('id_row'),
            'width' => Yii::$app->request->get('width'),
            'height' => Yii::$app->request->get('height'),
            'image' => Yii::$app->request->get('image'),
            'category' => Yii::$app->request->get('category'),
            'bgColor' => Yii::$app->request->get('bgColor'),
            'bgAlpha' => Yii::$app->request->get('bgAlpha'),
            'mode' => Yii::$app->request->get('mode'),
        ];

        foreach ($imageConfig as $k => $v) {
            if ($v === null) {
                unset($imageConfig[$k]);
            }
        }

        /* @var $display \pavlinter\display2\components\Display */
        $display = Yii::$app->get(Module::getInstance()->componentId);
        $image = $display->getImage($imageConfig);

        $img = \yii\imagine\Image::getImagine()->open($image->rootSrc);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'image/jpeg');
        $img->show('jpg');
        return;

    }
}
