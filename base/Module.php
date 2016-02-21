<?php

namespace app\base;

use Yii;
use yii\base\InvalidCallException;
use yii\base\UnknownPropertyException;

/**
 * Class Module
 */
class Module extends \yii\base\Module
{
    /**
     * Getter magic method.
     * This method is overridden to support accessing components like reading properties.
     * @param string $name component or property name
     * @return mixed the named property value
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        } else {
            if(Yii::$app->has($name))
            {
                return Yii::$app->get($name);
            }
            $getter = 'get' . $name;
            if (method_exists($this, $getter)) {
                // read property, e.g. getName()
                return $this->$getter();
            } else {
                // behavior property
                foreach ($this->getBehaviors() as $behavior) {
                    if ($behavior->canGetProperty($name)) {
                        return $behavior->$name;
                    }
                }
            }
            if (method_exists($this, 'set' . $name)) {
                throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
            } else {
                throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
            }
        }
    }
}
