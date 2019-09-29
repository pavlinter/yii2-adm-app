<?php

namespace app\components;

use Yii;

/**
 * Class Mailer
 */
class Mailer extends \yii\swiftmailer\Mailer
{
    private $_swiftMailer;

    public function getSwiftMailer()
    {
        if (!is_object($this->_swiftMailer)) {
            $this->_swiftMailer = $this->createSwiftMailer();
        }

        /*if (!$this->_transport->ping()) {
            $this->_transport->stop();
            $this->_transport->start();
        }*/

        return $this->_swiftMailer;
    }
}
