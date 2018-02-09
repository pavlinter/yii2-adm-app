<?php

namespace app\modules\appadm\controllers;

use app\modules\cloud\Cloud;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Cropper controller
 * 'controllerMap' => [
 *     'cropper' => [
 *         'class' => 'app\modules\appadm\controllers\CropperController',
 *     ],
 * ],
 */
class CropperController extends \app\modules\cropper\controllers\CropperController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \app\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager-tasks'],
                    ],
                    [
                        'actions' => ['avatar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionNews()
    {
        return $this->crop('dropzone_news', 1000, 500);
    }


    /**
     * @param $namespace
     * @param $minWidth
     * @param $minHeight
     * @return string
     * @throws \yii\base\Exception
     */
    public function crop($namespace, $minWidth, $minHeight)
    {
        /* @var $storage \app\modules\cloud\components\Storage */
        $storage = Cloud::getInst()->storage;
        $path = $storage->getPath($namespace);
        $webPath = $storage->getWebPath();
        $storage->removeCloudDir($namespace, true);

        $avatar_src = Yii::$app->request->post('avatar_src');
        $avatar_data = Yii::$app->request->post('avatar_data', '{}');
        $data = Json::decode($avatar_data);

        $model = new DynamicModel([
            'file' => UploadedFile::getInstanceByName('avatar_file')
        ]);

        /* @var $file \yii\web\UploadedFile */
        $file = $model->file;

        if (in_array($file->extension, ['png', 'jpg', 'gif'])) {
            $mb = 20; //20mb
        } else {
            $mb = 5; //5mb
        }

        $model->addRule('file', 'image', [
            'extensions' => 'gif, jpg, png',
            'maxSize' => 1024 * 1024 * $mb,
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
        ])->validate();

        $json['message'] = '';
        $json['result'] = '';


        if ($model->hasErrors()) {
            $json['message'] = $model->getFirstError('file');
        } else {



            if ($file->extension) {
                $file->name = uniqid() . '.' . $file->extension;
            }

            $box = new Box(round($data['width']), round($data['height']));

            if ($box->getWidth() < $minWidth || $box->getHeight() < $minHeight) {
                $json['message'] = Yii::t('app/cropper', 'Too much zoom!');
            } else {

                /* @var $img \Imagine\Gd\Image */
                $img = \yii\imagine\Image::getImagine()->open($file->tempName);
                $img->crop(new Point($data['x'], $data['y']), $box)
                    ->thumbnail(new Box($minWidth, $minHeight));

                $img = $this->minimizeImage($img);

                if ($img->save($path . $file->name)) {
                    $json['result'] = $webPath. $file->name;
                } else {
                    $json['message'] = Yii::t('app', 'Oops, something went wrong. Please try again!');
                }
            }
        }

        $json['state'] = 200;
        return Json::encode($json);
    }

    /**
     * @param $img
     * @return static
     * @throws \yii\base\InvalidConfigException
     */
    public function minimizeImage($img)
    {
        /* @var $resizeMode \pavlinter\display2\objects\ResizeModeMinRestrict */
        $resizeMode = Yii::createObject(['class' => 'pavlinter\display2\objects\ResizeModeMax']);

        $maxWidth = ArrayHelper::getValue(Yii::$app->params, 'dropzone.maxWidth');
        $maxHeight = ArrayHelper::getValue(Yii::$app->params, 'dropzone.maxHeight');

        if (!$maxWidth || !$maxHeight) {
            $maxWidth = ArrayHelper::getValue(Yii::$app->params, 'elfinder.maxWidth', 1600);
            $maxHeight = ArrayHelper::getValue(Yii::$app->params, 'elfinder.maxHeight', 1600);
        }

        /* @var $image \pavlinter\display2\objects\MockImage */
        $image = Yii::createObject([
            'class' => 'pavlinter\display2\objects\MockImage',
            'width' => $maxWidth,
            'height' => $maxHeight,
        ]);

        $img = $resizeMode->resize($image, $img);

        return $img;
    }


}
