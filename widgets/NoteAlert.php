<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', 'This is the message');
 * \Yii::$app->getSession()->setFlash('success', 'This is the message');
 * \Yii::$app->getSession()->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class NoteAlert extends \yii\base\Widget
{
    public $options = [];
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the Note type (i.e. danger, success, info, warning)
     */
    public $noteTypes = [
        'error'   => Note::TYPE_DANGER,
        'danger'  => Note::TYPE_DANGER,
        'success' => Note::TYPE_SUCCESS,
        'info'    => Note::TYPE_INFO,
        'warning' => Note::TYPE_WARNING,
    ];


    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->noteTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $message) {
                    /* assign unique id to each alert box */
                    $this->options['id'] = $this->getId() . '-' . $type;
                    echo Note::widget([
                        'body' => $message,
                        'options' => $this->options,
                        'type' => $this->noteTypes[$type],
                    ]);
                }
                $session->removeFlash($type);
            }
        }
    }
}
