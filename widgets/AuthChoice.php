<?php

namespace app\widgets;

use yii\authclient\widgets\AuthChoiceAsset;
use yii\authclient\widgets\AuthChoiceStyleAsset;
use yii\base\Widget;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\authclient\ClientInterface;


/**
 * Class AuthChoice
 * @package frontend\widgets
 */
class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    /**
     * @var boolean indicates if popup window should be used instead of direct links.
     */
    public $popupMode = true;
}
