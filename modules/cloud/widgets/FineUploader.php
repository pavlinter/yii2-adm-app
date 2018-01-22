<?php

namespace app\modules\cloud\widgets;

use app\modules\cloud\assets\Asset;
use app\modules\cloud\assets\FineUploaderAsset;
use app\modules\cloud\Cloud;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;

/**
 *
 *
 *  echo \app\modules\cloud\widgets\FineUploader::widget([
 *      'name' => 'own-session-name',
 *      'options' => [],
 *      'clientOptions' => [],
 *  ]);
 *
 */
class FineUploader extends \yii\base\Widget
{
    public $name;

    public $options = [];

    public $clientOptions = [];

    public $filesPath;

    public $filesUrlPath;


    public function init()
    {
        parent::init();
        Cloud::getInst();
        if (empty($this->name)) {
            throw new InvalidConfigException('The "name" property must be set.');
        }

        $this->options['id'] = ArrayHelper::remove($this->options, 'id', $this->getId());



        echo Html::script($this->getTemplate(), ['type' => 'text/template', 'id' => 'qq-template-gallery-' . $this->getId()]);
        echo Html::tag('div', null, $this->options);

        if ($this->filesPath && $this->filesUrlPath) {
            $this->registerScript(true);
            echo $this->showSavedFiles();
        } else {
            $this->registerScript();
        }
    }

    protected function registerScript($filePathExist)
    {
        $view = $this->getView();
        $assets = FineUploaderAsset::register($view);
        Asset::register($view);

        $clientOptions = ArrayHelper::merge([
            'template' => 'qq-template-gallery-' . $this->getId(),
            'request' => [
                'endpoint' => Url::to(['/cloud/fine-uploader/upload', 'name' => $this->name]),
            ],
            'deleteFile' => [
                'enabled' => true,
                'endpoint' => Url::to(['/cloud/fine-uploader/upload', 'name' => $this->name]),
            ],
            'thumbnails' => [
                'waitingPath' => $assets->getUrl("placeholders/waiting-generic.png"),
                'notAvailablePath' => $assets->getUrl("placeholders/not_available-generic.png"),
            ],
            'validation' => [
                'allowedExtensions' => ["jpeg", "jpg", "gif", "png", "txt"],
            ],
        ], $this->clientOptions);

        $view->registerJs('$("#' . $this->options['id'] . '").fineUploader(' . Json::encode($clientOptions) . ');');
        if ($filePathExist) {
            $view->registerJs('
                $(document).on("click" ,".fu-item-close", function(){
                    if(confirm("' . Yii::t(Cloud::TRANSLATION_KEY, 'Are you sure you want to delete this image?') . '")){
                        var $btn = $(this);
                        var filename = $btn.attr("data-filename");
                        var $li = $btn.closest(".fu-item");
                        var $loading = $li.find(".fu-loading");
                        $loading.show();
                        $.ajax({
                            url: "' . Url::current() .'",
                            type: "POST",
                            dataType: "json",
                            data: {filename : filename}
                        }).done(function(d){
                            if(d.r){
                                $li.remove();
                            }
                        }).always(function(jqXHR, textStatus){
                            $loading.hide();
                            if (textStatus !== "success") {

                            }
                        });
                    }
                });
            ');
        }
    }




    public function showSavedFiles()
    {
        $filesPath = Yii::getAlias($this->filesPath);
        $filesUrlPath = Yii::getAlias($this->filesUrlPath);
        $postFilename = Yii::$app->request->post('filename');

        if (Yii::$app->request->isAjax && $postFilename) {
            $json['r'] = true;
            // only need the content enclosed within this widget
            if (is_file($filesPath . '/' . $postFilename)) {
                unlink($filesPath . '/' . $postFilename);
            }
            $response = Yii::$app->getResponse();
            $response->clearOutputBuffers();
            $response->setStatusCode(200);
            $response->format = Response::FORMAT_JSON;
            $response->data = $json;
            $response->send();
            Yii::$app->end();
        }

        $files = FileHelper::findFiles($filesPath);
        $html = '';
        if ($files) {
            $html .= Html::beginTag('div', ['class' => 'fu-content',]);
            $html .= Html::beginTag('ul', ['class' => 'fu-list']);
            foreach ($files as $i => $file)
            {
                $filename = basename($file);
                $html .= Html::beginTag('li', ['class' => 'fu-item fu-item-'.$i]);
                $html .= Html::beginTag('div', ['class' => 'fu-box']);
                $html .= Html::tag('div', null ,['class' => "fu-overlay fu-loading"]);
                $html .= Html::img($filesUrlPath . '/' . $filename, ['class' => 'fu-item-img']);
                $html .= Html::a('<i class="fa fa-times"></i>', 'javascript:void(0);', ['class' => 'fu-item-close', 'data-filename' => $filename]);
                $html .= Html::endTag("div");
                $html .= Html::endTag("li");
            }
            $html .= Html::endTag("li");
            $html .= Html::endTag("div");
        }
        return $html;


    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return '
            <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                </div>
                <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                    <span class="qq-upload-drop-area-text-selector"></span>
                </div>
                <div class="qq-upload-button-selector qq-upload-button">
                    <div>Upload a file</div>
                </div>
                <span class="qq-drop-processing-selector qq-drop-processing">
                    <span>Processing dropped files...</span>
                    <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                </span>
                <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                    <li>
                        <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                        <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                        </div>
                        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                        <div class="qq-thumbnail-wrapper">
                            <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                        </div>
                        <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                        <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                            <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                            Retry
                        </button>

                        <div class="qq-file-info">
                            <div class="qq-file-name">
                                <span class="qq-upload-file-selector qq-upload-file"></span>
                                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                            </div>
                            <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                            <span class="qq-upload-size-selector qq-upload-size"></span>
                            <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                            </button>
                            <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                            </button>
                            <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                            </button>
                        </div>
                    </li>
                </ul>

                <dialog class="qq-alert-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Close</button>
                    </div>
                </dialog>

                <dialog class="qq-confirm-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">No</button>
                        <button type="button" class="qq-ok-button-selector">Yes</button>
                    </div>
                </dialog>

                <dialog class="qq-prompt-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <input type="text">
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Cancel</button>
                        <button type="button" class="qq-ok-button-selector">Ok</button>
                    </div>
                </dialog>
            </div>';
    }
}
