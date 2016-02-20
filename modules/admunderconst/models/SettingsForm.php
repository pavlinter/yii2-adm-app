<?php

namespace app\modules\admunderconst\models;

use app\modules\admunderconst\Module;
use pavlinter\admparams\Module as ParamsManager;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * SettingsForm
 */
class SettingsForm extends Model
{
    public $active = 0;

    public $imagePath = '/files/common/underconstruction.jpg';

    public function init()
    {
        if (isset(Yii::$app->params[Module::getInstance()->settingsKey])) {
            $params = Yii::$app->params[Module::getInstance()->settingsKey];
            foreach ($params as $name => $value) {
                if ($this->hasProperty($name)) {
                    $this->$name = $value;
                }
            }
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imagePath'], 'filter', 'filter' => function ($value) {
                return trim($value);
            },],
            [['imagePath'], 'string'],
            [['active'], 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'imagePath' => Yii::t('modelAdm/admunderconst', 'Image Path'),
            'active' => Yii::t('modelAdm/admunderconst', 'Active'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!isset(Yii::$app->params[Module::getInstance()->settingsKey])) {
            $path = Yii::getAlias('@webroot/files/common') . '/';
            if (!is_file($path . 'underconstruction.jpg')) {
                FileHelper::createDirectory($path);
                copy(Yii::getAlias('@webroot/modules/admunderconst/assets/images/underconstruction.jpg'), $path . 'underconstruction.jpg');
            }
        }
        return ParamsManager::getInstance()->manager->staticParams('change', Module::getInstance()->settingsKey, $this->getAttributes());
    }
}


