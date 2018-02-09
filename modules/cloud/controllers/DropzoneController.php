<?php

namespace app\modules\cloud\controllers;

use app\filters\AccessControl;
use app\helpers\ArrayHelper;
use app\modules\cloud\Cloud;
use app\modules\cloud\models\CroppicForm;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Class DropzoneController
 */
class DropzoneController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager-tasks'],
                    ],
                ],
            ],
        ];
    }
    /**
     * @param $name
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionRemove($name)
    {
        $module = Cloud::getInst();
        $filename = Yii::$app->request->post('filename');
        $uploadPath = $module->storage->getPath($name);
        if (is_file($uploadPath . $filename)) {
            unlink($uploadPath . $filename);
        }
        $module->storage->removeOldDir();
        return Json::encode(['r' => 1]);
    }


    /**
     * @param $name
     * @return bool|string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionUploadPhoto($name)
    {
        /* @var $storage \app\modules\cloud\components\Storage */
        $storage = Cloud::getInst()->storage;
        $fileName = 'file';

        $uploadPath = $storage->getPath($name);

        $files = $storage->getCloudFiles($name);
        $max_count_files = Cloud::getInst()->maxUploadFiles;
        if (count($files) >= $max_count_files) {
            Yii::$app->response->statusCode = 500;
            return Yii::t("app/dropzone", "Max files {count}", ['count' => $max_count_files, 'dot' => false]);
        }

        if (isset($_FILES[$fileName])) {
            $model = new DynamicModel([
                'file' => \yii\web\UploadedFile::getInstanceByName($fileName)
            ]);

            $validateMinWidth = ArrayHelper::getValue(Yii::$app->params, 'dropzone.validate.minWidth', 800);
            $validateMinHeight = ArrayHelper::getValue(Yii::$app->params, 'dropzone.validate.minHeight', 600);

            $model->addRule('file', 'image', [
                'maxSize' => 1024*1024 * 20, //5mb
                'extensions' => 'png, jpg, gif',
                'minWidth' => $validateMinWidth, //'maxWidth' => 5000,
                'minHeight' => $validateMinHeight, //'maxHeight' => 5000,
            ]);
            if ($model->validate()) {
                /* @var $file \yii\web\UploadedFile */
                $file = $model->file;

                $img = $this->resizeImage($file);

                if ($img->save($uploadPath . $file->name)) {
                    return Json::encode(['r' => 1]);
                } else {
                    throw new \yii\web\ServerErrorHttpException(Yii::t('app', 'Oops, something went wrong. Please try again!', ['dot' => false]));
                }
            } else {
                Yii::$app->response->statusCode = 500;
                return $model->getFirstError('file');
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return bool|string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionUploadFile($name)
    {
        /* @var $storage \app\modules\cloud\components\Storage */
        $storage = Cloud::getInst()->storage;
        $fileName = 'file';

        $uploadPath = $storage->getPath($name);

        $files = $storage->getCloudFiles($name);
        $max_count_files = Cloud::getInst()->maxUploadFiles;
        if (count($files) >= $max_count_files) {
            Yii::$app->response->statusCode = 500;
            return Yii::t("app/dropzone", "Max files {count}", ['count' => $max_count_files, 'dot' => false]);
        }

        if (isset($_FILES[$fileName])) {
            $model = new DynamicModel([
                'file' => \yii\web\UploadedFile::getInstanceByName($fileName)
            ]);
            /* @var $file \yii\web\UploadedFile */
            $file = $model->file;

            if (in_array($file->extension, ['png', 'jpg', 'gif'])) {
                $mb = 20; //20mb
            } else {
                $mb = 5; //5mb
            }
            $model->addRule('file', 'file', [
                'maxSize' => 1024*1024 * $mb,
                'extensions' => 'txt, pdf, xls, csv, doc, png, jpg, gif',
            ]);
            if ($model->validate()) {
                $img = $this->resizeImage($file, 'png, jpg, gif');
                if ($img !== false && $img->save($uploadPath . $file->name)) {
                    return Json::encode(['r' => 1]);
                } else if($img === false && $file->saveAs($uploadPath . $file->name)) {
                    return Json::encode(['r' => 1]);
                } else {
                    throw new \yii\web\ServerErrorHttpException(Yii::t('app', 'Oops, something went wrong. Please try again!', ['dot' => false]));
                }
            } else {
                Yii::$app->response->statusCode = 500;
                return $model->getFirstError('file');
            }
        }
        return false;
    }


    /**
     * @param $file $file \yii\web\UploadedFile
     * @param $extensions string|array|bool
     * @return bool|\Imagine\Gd\Image|static
     * @throws \yii\base\InvalidConfigException
     */
    public function resizeImage($file, $extensions = false)
    {
        if ($extensions !== false) {
            if (!is_array($extensions)) {
                $extensions = explode(',', $extensions);
            }
            $extensions = array_filter(array_map('trim', $extensions));

            if (!in_array($file->extension, $extensions)) {
                return false;
            }
        }

        /* @var $resizeMode \pavlinter\display2\objects\ResizeModeMinRestrict */
        $resizeMode = Yii::createObject(['class' => 'pavlinter\display2\objects\ResizeModeMax']);

        /* @var $img \Imagine\Gd\Image */
        $img = \yii\imagine\Image::getImagine()->open($file->tempName);


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
