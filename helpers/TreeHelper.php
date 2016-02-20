<?php

namespace app\helpers;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * TreeHelper
 */
class TreeHelper
{
    /**
     * @param $results
     * @param $items
     * @param $key
     * @param $value
     * @param $options
     */
    public static function treeMap(&$results, $items, $key, $value, $options)
    {
        $options = ArrayHelper::merge([
            'level' => 0,
            'space' => "&nbsp;&nbsp;",
            'levelOptions' => [],
            'itemsChilds' => 'childs',
            'value' => false,
        ], $options);


        foreach ($items as $item) {
            $space = "";
            $htmlOptions = ['tag' => 'span'];
            for ($i = 0; $i < $options['level']; $i++) {
                $space .= $options['space'];
            }
            if (isset($options['levelOptions'][$options['level']])) {
                $htmlOptions = ArrayHelper::merge($htmlOptions, $options['levelOptions'][$options['level']]);
            } elseif(isset($options['levelOptions']['else'])){
                $htmlOptions = ArrayHelper::merge($htmlOptions, $options['levelOptions']['else']);
            }



            $tag = ArrayHelper::remove($htmlOptions, 'tag');


            if ($options['value'] instanceof \Closure) {
                $result = call_user_func($options['value'], $tag, $space , $item->{$value}, $htmlOptions, $item);
            } else {
                $result =  Html::tag($tag, $space . $item->{$value}, $htmlOptions);
            }

            if ($key) {
                $results[$item->{$key}] = $result;
            } else {
                $results[] = $result;
            }


            if ($options['itemsChilds']) {
                $childs = $item->{$options['itemsChilds']};
                if($childs){
                    static::treeMap($results, $childs, $key, $value, ArrayHelper::merge($options, ['level' => $options['level'] + 1]));
                }
            }

        }
    }

    /**
     * @param $results
     * @param $item
     * @param $key
     * @param $value
     * @param $options
     * @return bool
     */
    public static function treeMapParent(&$results, $item, $key, $value, $options)
    {
        $options = ArrayHelper::merge([
            'level' => 0,
            'space' => "&nbsp;&nbsp;",
            'levelOptions' => [],
            'itemParent' => 'parent',
            'value' => false,
        ], $options);


        $parent = $item->{$options['itemParent']};

        if ($parent) {
            $space = "";
            $htmlOptions = ['tag' => 'span'];
            for ($i = 0; $i < $options['level']; $i++) {
                $space .= $options['space'];
            }
            if (isset($options['levelOptions'][$options['level']])) {
                $htmlOptions = ArrayHelper::merge($htmlOptions, $options['levelOptions'][$options['level']]);
            } elseif (isset($options['levelOptions']['else'])) {
                $htmlOptions = ArrayHelper::merge($htmlOptions, $options['levelOptions']['else']);
            }


            $tag = ArrayHelper::remove($htmlOptions, 'tag');


            if ($options['value'] instanceof \Closure) {
                $result = call_user_func($options['value'], $tag, $space, $item->{$value}, $htmlOptions, $parent);
            } else {
                $result = Html::tag($tag, $space . $item->{$value}, $htmlOptions);
            }

            if ($result) {
                if ($key) {
                    $results[$item->{$key}] = $result;
                } else {
                    $results[] = $result;
                }
                static::treeMapParent($results, $parent, $key, $value, ArrayHelper::merge($options, ['level' => $options['level'] + 1]));
            }
        }
    }
}
