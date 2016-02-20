<?php

namespace app\modules;

/**
 * Application is the base class for all web application classes.
 *
 * @property string $homeUrl The homepage URL.
 * @property \yii\web\Session $session The session component. This property is read-only.
 * @property \app\components\Display $display
 * @property \app\components\UrlManager $urlManager
 * @property \app\components\I18N $i18n
 */
class Application extends \yii\web\Application
{

}
