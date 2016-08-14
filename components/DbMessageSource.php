<?php

namespace app\components;

use Yii;


/**
 * @author Pavels Radajevs <pavlinter@gmail.com>
 */
class DbMessageSource extends \pavlinter\translation\DbMessageSource
{

    /**
     * Initializes this component.
     */
    public function init()
    {
        parent::init();
        //$this->on(static::EVENT_MISSING_TRANSLATION, function ($event) {
            /* @var $event \yii\i18n\MissingTranslationEvent */
            //$event->translatedMessage = '';
        //});
    }

}
