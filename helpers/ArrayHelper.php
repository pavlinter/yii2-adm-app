<?php

namespace app\helpers;

/**
 *
 */
class ArrayHelper extends \yii\helpers\BaseArrayHelper
{
    /**
     * @param $arr
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($arr, $key, $default = null)
    {
        if(isset($arr[$key])){
            return $arr[$key];
        }
        return $default;
    }
    /**
     * @param $data
     * @param $name
     * @param null $default
     * @param bool $strictArr
     * @return null
     */
    public static function getNested($data, $name, $default = null, $strictArr = false)
    {
        if(!is_array($data)){
            return $default;
        }

        if(is_array($name)){
            $keys = $name;
        } else {
            $keys = explode('.', $name);
        }

        foreach ($keys as $key) {
            array_shift($keys);
            if(isset($data[$key])){
                if(is_array($data[$key])){
                    if(sizeof($keys)){
                        return static::getNested($data[$key], $keys, $default, $strictArr);
                    }
                    if($strictArr){
                        return $data[$key];
                    }
                    return $default;
                } else {
                    return $data[$key];
                }
            }
        }
        return $default;
    }
}
