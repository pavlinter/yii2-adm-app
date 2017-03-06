<?php

namespace app\modules\admevents;

/**
 * @method \app\modules\admevents\models\SettingsForm staticPage
 * @method \app\modules\admevents\models\SettingsForm createPage
 * @method \app\modules\admevents\models\SettingsForm createPageQuery
 */
class ModelManager extends \pavlinter\adm\Manager
{
    /**
     * @var string|\app\modules\admevents\models\SettingsForm
     */
    public $settingsFormClass = 'app\modules\admevents\models\SettingsForm';
}