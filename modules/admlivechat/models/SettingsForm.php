<?php

namespace app\modules\admlivechat\models;

use app\modules\admlivechat\Module;
use pavlinter\admparams\Module as ParamsManager;
use Yii;
use yii\base\Model;

/**
 * SettingsForm
 */
class SettingsForm extends Model
{
    public $scripts;

    public $active = 1;

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
            [['scripts'], 'each', 'rule' => ['filter', 'filter' => 'trim']],
            [['active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scripts' => Yii::t('modelAdm/admlivechat', 'Live Chat Script'),
            'active' => Yii::t('modelAdm/admlivechat', 'Active'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        return ParamsManager::getInstance()->manager->staticParams('change', Module::getInstance()->settingsKey, $this->getAttributes());
    }
}


