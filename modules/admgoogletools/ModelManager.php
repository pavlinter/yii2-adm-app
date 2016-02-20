<?php

namespace app\modules\admgoogletools;

use pavlinter\adm\Manager;

/**
 * @method \app\modules\admgoogletools\models\SettingsForm staticPage
 * @method \app\modules\admgoogletools\models\SettingsForm createPage
 * @method \app\modules\admgoogletools\models\SettingsForm createPageQuery
 */
class ModelManager extends Manager
{
    /**
     * @var string|\app\modules\admgoogletools\models\SettingsForm
     */
    public $settingsFormClass = 'app\modules\admgoogletools\models\SettingsForm';
}