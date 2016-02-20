<?php

namespace app\modules\cloud\controllers;

use app\modules\cloud\Cloud;
use app\modules\cloud\components\UploadHandler;
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
 * Class FineUploaderController
 */
class FineUploaderController extends Controller
{
    /**
     * @return bool
     */
    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionUpload()
    {
        /* @var Cloud*/
        $cloud = Cloud::getInst();
        $path = $cloud->storage->getPath();

        $uploader = new UploadHandler();


        FileHelper::createDirectory($path);
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = []; // all files types allowed by default
        // Specify max file size in bytes.
        $uploader->sizeLimit = 20 * 1024 * 1024; // default is 10 MiB

        $method = Yii::$app->request->getMethod();

        if ($method == "POST") {
            // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
            // For example: /myserver/handlers/endpoint.php?done
            if (isset($_GET["done"])) {
                $result = $uploader->combineChunks($path);
            }
            // Handles upload requests
            else {
                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($path, uniqid());

                // To return a name used for uploaded file you can use the following line.
                $result["uploadName"] = $uploader->getUploadName();
            }

            return Json::encode($result);
        }
        // for delete file requests
        else if ($method == "DELETE") {
            $result = $uploader->handleDelete($path);
            return Json::encode($result);
        }
    }

    public function actionDelete()
    {

    }


}
