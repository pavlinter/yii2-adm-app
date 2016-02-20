<?php

namespace app\modules\cloud;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Cloud
 * @property \app\modules\cloud\components\Storage $storage
 *
 * //create new space
 * Cloud::getInst()->storage->setName('own-session-name');
 * Cloud::getInst()->storage->getPath();
 *
 * //move files
 * Cloud::getInst()->storage->moveFileAndClear('@webroot/new-place', 'own-session-name');
 * Cloud::getInst()->storage->moveFileTo('@webroot/new-place', 'own-session-name');
 *
 * //widgets
 *
 * \app\modules\cloud\widgets\FineUploader::widget([
 *     'name' => 'own-session-name',
 *     'filesPath' => '@webroot/path-to-saved-files',
 *     'filesUrlPath' => '@web/path-to-saved-files',
 * ])
 *
 */
class Cloud extends \yii\base\Module
{
    const TRANSLATION_KEY = 'cloud';

    public $controllerNamespace = 'app\modules\cloud\controllers';

    public $webCloudPath = '@web/cloud_cache';

    public $cloudPath = '@webroot/cloud_cache';


    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();
        $config = ArrayHelper::merge([
            'components' => [
                'storage' => [
                    'class' => 'app\modules\cloud\components\Storage',
                ],
            ],
        ], $config);
        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->webCloudPath = Yii::getAlias($this->webCloudPath) . '/';
        $this->cloudPath    = Yii::getAlias($this->cloudPath) . '/';
        parent::init();
    }
    /**
     * @return $this
     */
    public static function getInst()
    {
        return Yii::$app->getModule('cloud');
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations[static::TRANSLATION_KEY . '*'])) {
            Yii::$app->i18n->translations[static::TRANSLATION_KEY . '*'] = [
                'class' => 'pavlinter\translation\DbMessageSource',
                'forceTranslation' => true,
                'autoInsert' => true,
                'dotMode' => false,
            ];
        }
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if ($category) {
            $category = static::TRANSLATION_KEY . '/' . $category;
        } else {
            $category = static::TRANSLATION_KEY;
        }
        return Yii::t($category, $message, $params, $language);
    }
}
