<?php

namespace app\modules\admhidemenu;

use pavlinter\adm\Manager;

/**
 * @method \app\modules\admhidemenu\models\SettingsForm staticPage
 * @method \app\modules\admhidemenu\models\SettingsForm createPage
 * @method \app\modules\admhidemenu\models\SettingsForm createPageQuery
 */
class ModelManager extends Manager
{
    /**
     * @var string|\app\modules\admhidemenu\models\SettingsForm
     */
    public $settingsFormClass = 'app\modules\admhidemenu\models\SettingsForm';
}