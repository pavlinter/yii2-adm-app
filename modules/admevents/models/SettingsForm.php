<?php

namespace app\modules\admevents\models;

use app\modules\admevents\Module;
use pavlinter\admparams\Module as ParamsManager;
use Yii;
use yii\base\Model;

/**
 * SettingsForm
 */
class SettingsForm extends Model
{
    public $head;

    public $beginBody;

    public $endBody;

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
            [['head', 'beginBody', 'endBody'], 'string'],
            [['active'], 'boolean'],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'head' => Yii::t('modelAdm/admevents', 'Head'),
            'beginBody' => Yii::t('modelAdm/admevents', 'Begin Body'),
            'endBody' => Yii::t('modelAdm/admevents', 'End Body'),
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


