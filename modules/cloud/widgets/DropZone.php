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
 * echo \app\modules\cloud\widgets\DropZone::widget([
 *      'uploadUrl' => ['/site/upload-photo'],
 *      'removeUrl' => ['/site/remove-upload-photo'],
 *      'files' => [
 *          [
 *              'url' => 'url to image',
 *              'name' => 'image name',
 *              'size' => 'image size',
 *          ],
 *      ],
 *      //'cloudFilesName' => 'own-session-name',
 *      'pluginOptions' => [
 *          'maxFilesize' => 4, //4mb
 *          'maxFiles' => 6,
 *      ],
 *      'pluginEvents' => [],
 * ]);
 *
 */
class DropZone extends \yii\base\Widget
{
    const THEME_DEFAULT = 'default';

    public $id = 'myDropzone';
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

    public $dropzoneContainer = 'myDropzone';
    public $previewsContainer = 'previews';
    public $autoDiscover = false;

    public $theme = self::THEME_DEFAULT;

    /**
     * Initializes the widget
     * @throw InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->uploadUrl)) {
            throw new InvalidConfigException('The "uploadUrl" property must be set.');
        }

        if ($this->removeUrl) {
            $this->removeUrl = Url::to($this->removeUrl);
            if (!isset($this->pluginEvents['removedfile'])) {
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
                    }
                }';
            }
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
            $files = $storage->getCloudFiles($this->cloudFilesName);

            $path = $storage->getPath();
            $webPath = $storage->getWebPath();

            foreach ($files as $filepath) {
                $this->files[] = [
                    'url' => str_replace($path, $webPath, $filepath),
                    'name' => basename($filepath),
                    'size' => filesize($filepath),
                    //'size' => filesize(Url::to('@webroot' . strtr($file, [DIRECTORY_SEPARATOR => '']))),
                ];
            }
        }




        $this->registerAssets();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        return Html::tag('div', $this->renderDropzone(), ['id' => $this->dropzoneContainer, 'class' => 'dropzone']);
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

        $view->registerJs($js);
        DropZoneAsset::register($view);
        if ($this->theme) {
            DropZoneThemeAsset::register($view)->addTheme($this->theme);
        }
    }
}
