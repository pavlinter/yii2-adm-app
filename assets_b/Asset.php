<?php

namespace app\assets_b;

/**
 * Class Asset
 */
class Asset extends \yii\web\AssetBundle
{
    public $forcePublish;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if($this->forcePublish === null){
            $this->forcePublish = YII_ENV_PROD;
        }
        if($this->basePath && $this->forcePublish){
            if($this->sourcePath === null){
                $this->sourcePath = $this->basePath;
            }
            $this->basePath = null;
            $this->baseUrl = null;
        }
        parent::init();
    }
}
