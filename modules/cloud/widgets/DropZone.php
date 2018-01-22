<?php

namespace app\modules\cloud\widgets;

use app\modules\cloud\assets\DropZoneAsset;
use app\modules\cloud\assets\DropZoneThemeAsset;
use app\modules\cloud\Cloud;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class DropZone
 *
 * //controller
 * if($model->save()){
 *      $cloud = \app\modules\cloud\Cloud::getInst();
 *      $config = Yii::$app->display->getCategoryConfig('banner');
 *      $cloud->storage->moveFileAndClear($config['imagesDir'] . '/' . $model->id . '/main', 'own-namespace');
 *      DropZone::removeFiles($model->id, 'banner', 'main');
 * }
 *
 * //view
 * echo \app\modules\cloud\widgets\DropZone::widget([
 *      'theme' => \app\modules\cloud\widgets\DropZone::THEME_FULL_IMAGE,
 *      //'uploadUrl' => ['/cloud/dropzone/upload-photo', '?' => ['name' => 'own-namespace'],],
 *      //'removeUrl' => ['/site/remove-upload-photo'], //not need if use 'cloudFilesName'
 *      'isNewRecord' => $model->isNewRecord,
 *      'files' => \app\modules\cloud\widgets\DropZone::displayFiles($model->id, 'banner', [
 *          'mode' => \pavlinter\display2\objects\Image::MODE_STATIC,
 *          'bgColor' => 'ffffff',
 *          'width' => 200,
 *          'height' => 200,
 *      ], [
 *          'dir' => 'dirname',
 *      ]),
 *      'cloudFilesName' => 'own-session-name',
 *      'pluginOptions' => [
 *          'maxFilesize' => 4, //4mb
 *          'maxFiles' => 6,
 *      ],
 *      'pluginEvents' => [],
 * ]);
 *
 *
 */
class DropZone extends \yii\base\Widget
{
    const THEME_DEFAULT = 'default';
    const THEME_FULL_IMAGE = 'full_image';
    const THEME_SINGULAR = 'singular';

    public $id;
    /**
     * @var array An array of options that are supported by Dropzone
     */
    public $options = [];
    /**
     * @var array An array of client events that are supported by Dropzone
     */
    public $pluginOptions = [];
    /**
     * @var array An array of client events that are supported by Dropzone
     */
    public $pluginEvents = [];

    public $uploadUrl;

    public $removeUrl;

    /**
     * @var array exists files
     * example array
     * [
     *      [
     *          'url' => 'url to image',
     *          'name' => 'image name',
     *          'size' => 'image size',
     *      ],
     * ]
     */
    public $files = [];

    /**
     * @var string get files from Cloud::getInst()->storage->getCloudFiles($this->cloudFilesName)
     */
    public $cloudFilesName;

    public $deleteInputName = 'deleteMock';

    public $clearExistDir = true; //очищаем папку если существуют данные в $this->files

    public $dropzoneContainer;
    public $previewsContainer;
    public $autoDiscover = false;

    public $theme = self::THEME_DEFAULT;

    public $dropzoneContainerOptions = [];

    public $isNewRecord = true;

    public $uploadRouter = '/cloud/dropzone/upload-photo';

    public $removeRouter = '/cloud/dropzone/remove';
    /**
     * Initializes the widget
     * @throw InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->id === null)  {
            $this->id = $this->getId();
        }

        if ($this->dropzoneContainer === null) {
            $this->dropzoneContainer = $this->id . '-container';
        }

        if ($this->previewsContainer === null) {
            $this->previewsContainer = $this->id . '-previews';
        }

        if ($this->cloudFilesName) {
            if (empty($this->uploadUrl)) {
                $this->uploadUrl = [$this->uploadRouter, '?' => [
                    'name' => $this->cloudFilesName,
                ]];
            }
        } elseif (empty($this->uploadUrl)){
            throw new InvalidConfigException('The "uploadUrl" property must be set.');
        }


        if ($this->cloudFilesName && $this->removeUrl === null) {
            $this->removeUrl = [$this->removeRouter, '?' => [
                'name' => $this->cloudFilesName,
            ]];
        }

        if ($this->removeUrl) {
            $this->removeUrl = Url::to($this->removeUrl);
            if (!isset($this->pluginEvents['removedfile'])) {

                $singularJs1 = '';
                $singularJs2 = '';
                if ($this->theme == static::THEME_SINGULAR) {
                    $singularJs2 = '$("#' . $this->dropzoneContainer . '" ).removeClass("dropzone-uploaded");';
                }

                if ($this->deleteInputName) {
                    $singularJs1 .= '
                        if(file.isMock){ 
                            var file_id = file.name;
                            if (file.id) {
                                file_id = file.id;
                            }
                            $("#' . $this->dropzoneContainer . '" ).append(\'<input type="hidden" name="' . $this->deleteInputName . '[]" value="\' + file_id + \'">\'); 
                        }
                    ';
                }

                $this->pluginEvents['removedfile'] = 'function(file){
                    if(file.status === Dropzone.SUCCESS || file.status === Dropzone.ADDED){
                        $.ajax({
                            url: "' . $this->removeUrl . '",
                            type: "POST",
                            dataType: "json",
                            data: {filename: file.name}
                        }).done(function(d){

                        }).fail(function(jqXHR, textStatus, message){
                            alert(message);
                        });
                        ' . $singularJs1 . '
                    }
                    ' . $singularJs2 . '
                }';
            }
        }

        if ($this->theme == static::THEME_SINGULAR && !isset($this->pluginEvents['sending'])) {
            $this->pluginEvents['sending'] = 'function(file){
                $("#' . $this->dropzoneContainer . '" ).addClass("dropzone-uploaded");  
            }';
        }

        if ($this->theme == static::THEME_FULL_IMAGE && !isset($this->pluginEvents['addedfile'])) {
            $this->pluginEvents['addedfile'] = 'function(file){
                 $("#' . $this->dropzoneContainer . ' .dz-image img").on("load", function () {
                     var $img = $(this);
                     var w = $img.width();
                     var h = $img.height();
                     if(w > h){
                        $img.width(200);
                        var marginTop = (200 - $img.height()) / 2;
                        if(marginTop > 0){
                            $img.css({marginTop: marginTop + "px"});
                        }
                     } else if (w < h) {
                        $img.height(200);
                     } else {
                        $img.width(200);
                        $img.height(200);
                     }
                     $img.addClass("dz-img-loaded");
                 });
             }';
        }


        $this->pluginOptions = ArrayHelper::merge([
            "url" => Url::to($this->uploadUrl),
            "previewsContainer" => "#" . $this->previewsContainer,
            "clickable" => true,
            "dictDefaultMessage" => Yii::t('app/dropzone', "Drop files here to upload", ['dot' => false]),
            "dictFallbackMessage" => Yii::t('app/dropzone', "Your browser does not support drag'n'drop file uploads.", ['dot' => false]),
            "dictFallbackText" => Yii::t('app/dropzone', "Please use the fallback form below to upload your files like in the olden days.", ['dot' => false]),
            "dictFileTooBig" => Yii::t('app/dropzone', "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", ['dot' => false]),
            "dictInvalidFileType" => Yii::t('app/dropzone', "You can't upload files of this type.", ['dot' => false]),
            "dictResponseError" => Yii::t('app/dropzone', "Server responded with {{statusCode}} code.", ['dot' => false]),
            "dictCancelUpload" => Yii::t('app/dropzone', "Cancel upload", ['dot' => false]),
            "dictCancelUploadConfirmation" => Yii::t('app/dropzone', "Are you sure you want to cancel this upload?", ['dot' => false]),
            "dictRemoveFile" => Yii::t('app/dropzone', "Remove file", ['dot' => false]),
            "dictMaxFilesExceeded" => Yii::t('app/dropzone', "You can not upload any more files.", ['dot' => false]),
        ], $this->pluginOptions);

        if ($this->theme == static::THEME_SINGULAR) {
            $this->pluginOptions['dictDefaultMessage'] = Yii::t('app/dropzone', "Загрузить фото <br/>(jpg, gif, png)");
            if (!isset($this->pluginOptions['thumbnailWidth'])) {
                $this->pluginOptions['thumbnailWidth'] = null;
            }
            if (!isset($this->pluginOptions['thumbnailWidth'])) {
                $this->pluginOptions['thumbnailHeight'] = null;
            }
        }

        if ($this->theme == static::THEME_FULL_IMAGE) {
            if (!isset($this->pluginOptions['thumbnailWidth'])) {
                $this->pluginOptions['thumbnailWidth'] = null;
            }
            if (!isset($this->pluginOptions['thumbnailWidth'])) {
                $this->pluginOptions['thumbnailHeight'] = null;
            }
        }

        if (isset($this->pluginEvents['removedfile'])) {
            $this->pluginOptions['addRemoveLinks'] = true;
        }

        $this->autoDiscover = $this->autoDiscover === false ? 'false' : 'true';

        if(Yii::$app->getRequest()->enableCsrfValidation){
            $this->pluginOptions['headers'][\yii\web\Request::CSRF_HEADER] = Yii::$app->getRequest()->getCsrfToken();
            $this->pluginOptions['params'][Yii::$app->getRequest()->csrfParam] = Yii::$app->getRequest()->getCsrfToken();
        }

        if ($this->cloudFilesName) {
            /* @var $storage \app\modules\cloud\components\Storage */
            $storage = Cloud::getInst()->storage;

            if (!$this->isNewRecord) {
                $storage->removeCloudDir($this->cloudFilesName);
            }

            $files = $storage->getCloudFiles($this->cloudFilesName);

            $path = $storage->getPath();
            $webPath = $storage->getWebPath();

            $existFiles = $this->files;
            foreach ($files as $filepath) {
                $this->files[] = [
                    'url' => str_replace($path, $webPath, $filepath),
                    'name' => basename($filepath),
                    'size' => filesize($filepath),
                    //'size' => filesize(Url::to('@webroot' . strtr($file, [DIRECTORY_SEPARATOR => '']))),
                ];
            }
            if ($this->clearExistDir && $existFiles) {
                $storage->removeCloudDir($this->cloudFilesName);
            }
        }

        if ($this->theme == static::THEME_SINGULAR && count($this->files)) {
            Html::addCssClass($this->dropzoneContainerOptions, 'dropzone-uploaded');
        }

        $this->registerAssets();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $options = $this->dropzoneContainerOptions;
        Html::addCssClass($options, 'dropzone');
        if ($this->theme) {
            Html::addCssClass($options, 'dropzone-' . $this->theme);
        }
        $options['id'] = $this->dropzoneContainer;
        return Html::tag('div', $this->renderDropzone(), $options);
    }

    /**
     * @return string
     */
    private function renderDropzone()
    {
        $data = Html::tag('div', '', ['id' => $this->previewsContainer, 'class' => 'dropzone-previews']);

        return $data;
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();


        $js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . '; var ' . $this->id . ' = new Dropzone("div#' . $this->dropzoneContainer . '", ' . Json::encode($this->pluginOptions) . ');';

        if ($this->files) {
            $js .= '
                var mockFiles = ' . Json::encode($this->files) .';
                jQuery.each(mockFiles, function(key, mock) {
                    mock.isMock = true;
                    mock.status = Dropzone.ADDED;
                    mock.accepted = true;
                    ' . $this->id . '.emit("addedfile", mock);
                    ' . $this->id . '.emit("thumbnail", mock, mock.url);
                    ' . $this->id . '.emit("complete", mock);
                });
            ';
        }

        if (!empty($this->pluginEvents)) {
            foreach ($this->pluginEvents as $event => $handler) {
                $js .= "$this->id.on('$event', $handler);";
            }
        }

        if ($this->theme == static::THEME_FULL_IMAGE) {
            $js .= '
                $("#' . $this->dropzoneContainer . ' .dz-image img").on("load", function () {
                     var $img = $(this);
                     var w = $img.width();
                     var h = $img.height();
                     if(w > h){
                        $img.width(200);
                        var marginTop = (200 - $img.height()) / 2;
                        if(marginTop > 0){
                            $img.css({marginTop: marginTop + "px"});
                        }
                     } else if (w < h) {
                        $img.height(200);
                     } else {
                        $img.width(200);
                        $img.height(200);
                     }
                     $img.addClass("dz-img-loaded");
                 });
                 
            ';
        }

        $view->registerJs($js);
        DropZoneAsset::register($view);
        if ($this->theme) {
            DropZoneThemeAsset::register($view)->addTheme($this->theme);
        }
    }

    /**
     * @param $id
     * @param $category
     * @param array $config
     * @param array $options
     * @return array
     */
    public static function displayFiles($id, $category, $config = [], $options = [])
    {
        $photosData = Yii::$app->display->getFileImgs($id, $category, $config, $options);

        $files = [];
        foreach ($photosData as $photoData) {
            $files[] = [
                'url' => $photoData['display'],
                'name' =>  $photoData['image'],
                'size' => filesize($photoData['fullPath']),
            ];
        }

        return $files;
    }

    /**
     * @param $id
     * @param $category
     * @param string $dir
     * @param string $deleteInputName
     * @return array|bool
     * @throws InvalidConfigException
     */
    public static function removeFiles($id, $category, $dir = 'main', $deleteInputName = 'deleteMock')
    {
        $delete = Yii::$app->request->post($deleteInputName, []);

        $files = Yii::$app->display->getRowFiles($id, $category, [
            'dir' => $dir,
        ]);

        foreach ($delete as $image) {
            if(isset($files[$image])){
                @unlink($files[$image]['fullPath']);
                unset($files[$image]);
            }
        }

        return $files;
    }


}
