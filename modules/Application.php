<?php

namespace app\modules;

/**
 * Application is the base class for all web application classes.
 *
 * @property string $homeUrl The homepage URL.
 * @property \app\components\User $user
 * @property \app\components\DbManager $authManager
 * @property \app\components\Shortcode $shortcode
 * @property \app\components\UrlManager $urlManager
 * @property \app\components\I18N $i18n
 * @property \app\components\AssetManager $assetManager
 * @property \app\components\View $view
 * @property \app\components\Request $request
 * @property \app\components\Response $response
 * @property \app\components\Formatter $formatter
 * @property \app\components\Display $display
 * @property \app\components\FileCache $cache
 * @property \app\components\ErrorHandler $errorHandler
 * @property \app\components\Mailer $mailer
 * @property \app\components\Security $security
 * @property \app\components\Session $session
 * @property \app\components\Dispatcher $log
 * @property \app\components\Connection $db
 * @property \app\components\MobileDetect $mobileDetect
 * @property \app\modules\activeResponse\components\ActiveResponse $ar
 *
 */
class Application extends \yii\web\Application
{

}
