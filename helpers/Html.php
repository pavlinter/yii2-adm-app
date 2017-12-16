<?php

namespace app\helpers;


/**
 * Html provides a set of static methods for generating commonly used HTML tags
 */
class Html extends \yii\helpers\BaseHtml
{
    /**
     * Encodes special characters into HTML entities.
     * The [[\yii\base\Application::charset|application charset]] will be used for encoding.
     *
     * @param string $content the content to be encoded
     * @param integer $flags for htmlspecialchars
     * @param bool $doubleEncode whether to encode HTML entities in `$content`. If false,
     * HTML entities in `$content` will not be further encoded.
     * @return string the encoded content
     * @see decode()
     * @see http://www.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function encode($content, $flags = ENT_NOQUOTES | ENT_SUBSTITUTE, $doubleEncode = true)
    {
        return htmlspecialchars($content, $flags, \Yii::$app ? \Yii::$app->charset : 'UTF-8', $doubleEncode);
    }
}
