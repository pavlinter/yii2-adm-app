<?php

namespace app\base\behaviors;
use yii\helpers\Inflector;


/**
 * Class SluggableBehavior
 * @package app\base\behaviors
 */
class SluggableBehavior extends \yii\behaviors\SluggableBehavior
{
    public $slugAttribute = 'alias';

    public $attribute = 'title';

    public $immutable = true; //if set false , always generate new slug

    public $ensureUnique = true;

    /**
     * This method is called by [[getValue]] to generate the slug.
     * You may override it to customize slug generation.
     * The default implementation calls [[\yii\helpers\Inflector::slug()]] on the input strings
     * concatenated by dashes (`-`).
     * @param array $slugParts an array of strings that should be concatenated and converted to generate the slug value.
     * @return string the conversion result.
     */
    protected function generateSlug($slugParts)
    {
        return static::slug(implode('-', $slugParts));
    }


    /**
     * @param $string
     * @param string $replacement
     * @param bool $lowercase
     * @return string
     */
    public static function slug($string, $replacement = '-', $lowercase = true)
    {
        //in localhost russian char is not work.
        $string = Inflector::transliterate($string, 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;');
        $string = preg_replace('/[^a-zA-Z0-9=\s—–-]+/u', '', $string);
        $string = preg_replace('/[=\s—–-]+/u', $replacement, $string);
        $string = trim($string, $replacement);

        return $lowercase ? strtolower($string) : $string;
    }
}

