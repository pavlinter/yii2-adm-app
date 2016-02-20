<?php

namespace app\modules\admhidemenu\models;

use app\modules\admhidemenu\Module;
use pavlinter\admparams\Module as ParamsManager;
use Yii;
use yii\base\Model;

/**
 * SettingsForm
 */
class SettingsForm extends Model
{
    public $items;

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
            [['items'], 'itemsFilter'],
        ];
    }

    public function itemsFilter($attribute, $params)
    {

        $items = $this->$attribute;
        if (is_array($items)) {
            foreach ($items as $i => $item) {
                if ($item) {
                    $items[$i] = 1;
                } else {
                    $items[$i] = 0;
                }
            }
            $this->$attribute = $items;
        } else {
            $this->$attribute = [];
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'items' => Yii::t('modelAdm/admhidemenu', 'Items'),
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


