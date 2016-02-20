<?php

namespace app\modules\admlivechat;

use pavlinter\adm\Manager;

/**
 * @method \app\modules\admlivechat\models\SettingsForm staticPage
 * @method \app\modules\admlivechat\models\SettingsForm createPage
 * @method \app\modules\admlivechat\models\SettingsForm createPageQuery
 */
class ModelManager extends Manager
{
    /**
     * @var string|\app\modules\admlivechat\models\SettingsForm
     */
    public $settingsFormClass = 'app\modules\admlivechat\models\SettingsForm';
}