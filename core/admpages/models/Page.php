<?php

namespace app\core\admpages\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @method \pavlinter\translation\TranslationBehavior getLangModels
 * @method \pavlinter\translation\TranslationBehavior setLanguage
 * @method \pavlinter\translation\TranslationBehavior getLanguage
 * @method \pavlinter\translation\TranslationBehavior saveTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAllTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAll
 * @method \pavlinter\translation\TranslationBehavior validateAll
 * @method \pavlinter\translation\TranslationBehavior validateLangs
 * @method \pavlinter\translation\TranslationBehavior loadAll
 * @method \pavlinter\translation\TranslationBehavior loadLang
 * @method \pavlinter\translation\TranslationBehavior loadLangs
 * @method \pavlinter\translation\TranslationBehavior getTranslation
 * @method \pavlinter\translation\TranslationBehavior hasTranslation
 *
 * @property string $id
 * @property string $id_parent
 * @property string $layout
 * @property string $type
 * @property string $weight
 * @property integer $visible
 * @property integer $active
 * @property string $date
 *
 * Translation
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $image
 * @property string $alias
 * @property string $url
 * @property string $text
 *
 * @property \pavlinter\admpages\models\PageLang[] $translations
 * @property Page $parent
 * @property Page[] $childs
 */
class Page extends \pavlinter\admpages\models\Page
{
    /**
     * @param bool $onlyshort
     * @return bool|string
     */
    public function shortText($onlyshort = false)
    {
        $pos = strpos($this->text, self::$textBreak);
        if ($pos !== false) {
            return trim(mb_substr($this->text, 0, $pos));
        }
        if ($onlyshort) {
            return false;
        }
        return $this->text;
    }
}
