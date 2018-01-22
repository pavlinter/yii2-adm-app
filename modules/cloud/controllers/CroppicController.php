<?php

namespace app\modules\cloud\controllers;

use app\modules\cloud\Cloud;
use app\modules\cloud\models\CroppicForm;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Class CroppicController
 */
class CroppicController extends Controller
{
    public function actionIndex()
    {
        return $this->hash('dddddddd');
    }

    public function hash($path)
    {
        if (is_callable($this->hashCallback)) {
            return call_user_func($this->hashCallback, $path);
        }
        $path = (is_file($path) ? dirname($path) : $path) . filemtime($path);
        return sprintf('%x', crc32($path . Yii::getVersion()));
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionSaveImg()
    {
        $croppicForm = new CroppicForm();
        $croppicForm->file = UploadedFile::getInstanceByName('img');

        if ($croppicForm->file && $croppicForm->validate()) {
            $path = Cloud::getInst()->cloudPath;
            $pathWeb = Cloud::getInst()->webCloudPath;
            FileHelper::createDirectory($path);
            $croppicForm->file->name = uniqid('big_') . '.' . $croppicForm->file->extension;
            $croppicForm->file->saveAs($path . $croppicForm->file->baseName . '.' . $croppicForm->file->extension);

            list($width, $height) = getimagesize($path . $croppicForm->file->name);
            $json['status'] = 'success';
            $json['url'] = Url::to($pathWeb . $croppicForm->file->name, true);
            $json['width'] = $width;
            $json['height'] = $height;
            return Json::encode($json);
        }
        $json['status'] = 'error';
        $errors = $croppicForm->getFirstErrors();
        if ($errors) {
            $json['message'] = reset($errors);
        } else {
            $json['message'] = 'Oops, something went wrong. Please try again!';
        }
        return Json::encode($json);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionCropImg()
    {

        $path = Cloud::getInstance()->cloudPath;
        $pathWeb = Cloud::getInstance()->webCloudPath;

        FileHelper::createDirectory($path);

        $filename = basename(Yii::$app->request->post('imgUrl'));
        // original sizes
        $imgInitW = Yii::$app->request->post('imgInitW');
        $imgInitH = Yii::$app->request->post('imgInitH');
        // resized sizes
        $imgW = Yii::$app->request->post('imgW');
        $imgH = Yii::$app->request->post('imgH');
        // offsets
        $imgX1 = Yii::$app->request->post('imgX1');
        $imgY1 = Yii::$app->request->post('imgY1');
        // crop box
        $cropW = Yii::$app->request->post('cropW');
        $cropH = Yii::$app->request->post('cropH');
        // rotation angle
        $angle = Yii::$app->request->post('rotation');

        $originalImage = Image::getImagine()->open($path . $filename);
        $cropFilename = strtr($filename, ['big_' => 'crop_']);
        $originalImage->resize(new Box($imgW, $imgH))
            ->rotate($angle)
            ->crop(new Point($imgX1, $imgY1), new Box($cropW, $cropH));


        $originalImage->save($path . $cropFilename);
        if(strpos($filename, 'big_') === 0) {
            unlink($path . $filename);
        }

        $json['status'] = 'success';
        $json['url'] = Url::to($pathWeb . $cropFilename, true);
        return Json::encode($json);


    }

    /**
     * @param $id
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionDeleteImg($id)
    {
        $model = EUtemplate::find()->where(['id' => $id, 'user_id' => Yii::$app->user->getId()])->one();
        $json['r'] = 0;
        if ($model !== null) {
            $file = basename(Yii::$app->request->post('file'));
            $path = Yii::getAlias('@webroot/files/utemplate/' . $id) . '/';
            if(is_file($path . $file)){
                unlink($path . $file);
            }
            $json['r'] = 1;
        }
        return Json::encode($json);
    }
}
