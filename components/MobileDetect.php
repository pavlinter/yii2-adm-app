<?php

namespace app\components;

/**
 * Class MobileDetect
 * @package app\components
 */
class MobileDetect extends \ezze\yii2\mobiledetect\MobileDetect
{
    /**
     * @var \Mobile_Detect
     */
    protected $mobileDetect;

    /**
     * @return bool
     */
    public function isMobile()
    {
        return $this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet();
    }
}