<?php

namespace app\modules\cropper\controllers;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Cropper controller
 * 'controllerMap' => [
 *     'cropper' => [
 *         'class' => 'app\modules\cropper\controllers\CropperController',
 *     ],
 * ],
 */
class CropperController extends Controller
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
    public function actionAvatar()
    {
        $user_id = Yii::$app->user->getId();
        $path = '@webroot/files/data/user/' . $user_id .'/main/';
        $minSize = 600;
        $avatar_src = Yii::$app->request->post('avatar_src');
        $avatar_data = Yii::$app->request->post('avatar_data', '{}');
        $data = Json::decode($avatar_data);
        $path = FileHelper::normalizePath(Yii::getAlias($path)) . DIRECTORY_SEPARATOR;
        $file = UploadedFile::getInstanceByName('avatar_file');

        $model = new DynamicModel(compact('file'));
        $model->addRule('file', 'image', [
            'extensions' => 'gif, jpg, png',
            'maxSize' => 1024 * 1024 * 5,
            'minWidth' => $minSize,
            'minHeight' => $minSize,
        ])->validate();
        /* @var $file \yii\web\UploadedFile */
        $file = $model->file;
        $json['message'] = '';
        $json['result'] = '';

        if ($model->hasErrors()) {
            $json['message'] = $model->getFirstError('file');
        } else {
            if ($file->extension) {
                $file->name = uniqid() . '.' . $file->extension;
            }
            FileHelper::removeDirectory($path);
            if (!FileHelper::createDirectory($path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }

            $originalImage = Image::getImagine()->open($file->tempName);
            $box = new Box(round($data['width']), round($data['height']));

            if ($box->getWidth() < $minSize || $box->getHeight() < $minSize) {
                $json['message'] = Yii::t('app/cropper', 'Too much zoom!');
            } else {
                $newImage = $originalImage->crop(new Point($data['x'], $data['y']), $box)
                    ->thumbnail(new Box($minSize, $minSize));

                $r = $newImage->save($path . $file->name);

                if ($r) {
                    $photoData = Yii::$app->display->getFileImg($user_id, 'user', [
                        'width' => 400,
                        'height' => 400,
                    ], [
                        'dir' => 'main',
                    ]);
                    $json['result'] = $photoData['display'];
                } else {
                    $json['message'] = Yii::t('app', 'Oops, something went wrong. Please try again!');
                }
            }
        }

        $json['state'] = 200;
        return Json::encode($json);
    }
}
