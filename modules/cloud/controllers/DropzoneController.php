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
 * Class DropzoneController
 */
class DropzoneController extends Controller
{
    /**
     * @param $name
     * @return string
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
}
