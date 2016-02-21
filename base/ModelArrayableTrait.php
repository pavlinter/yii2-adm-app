<?php

namespace app\base;

/**
 * Class ModelArrayableTrait
 */
trait ModelArrayableTrait
{
    private $_to_array = null;

    /**
     * @return array
     */
    public function fields()
    {
        return $this->traitFields();
    }

    /**
     * @return array
     */
    public function traitFields()
    {
        $fields = parent::fields();
        return \yii\helpers\ArrayHelper::merge($fields, $this->langFields());
    }

    /**
     * @return array
     */
    public function langFields()
    {
        $trans = $this->getBehavior('trans');
        if($trans instanceof \pavlinter\translation\TranslationBehavior){
            $langfields = array_keys($trans->langToArray());
            return array_combine($langfields, $langfields);
        }
        return [];
    }

    /**
     * @param $name
     * @param null $default
     * @return null|array
     */
    public function getField($name, $default = null)
    {
        if($this->_to_array === null){
            $this->_to_array = $this->toArray();
        }
        if(isset($this->_to_array[$name])){
            return $this->_to_array[$name];
        }
        return $default;
    }
}
