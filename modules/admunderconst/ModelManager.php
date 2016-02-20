<?php

namespace app\modules\admunderconst;

use pavlinter\adm\Manager;

/**
 * @method \app\modules\admunderconst\models\SettingsForm staticPage
 * @method \app\modules\admunderconst\models\SettingsForm createPage
 * @method \app\modules\admunderconst\models\SettingsForm createPageQuery
 */
class ModelManager extends Manager
{
    /**
     * @var string|\app\modules\admunderconst\models\SettingsForm
     */
    public $settingsFormClass = 'app\modules\admunderconst\models\SettingsForm';
}