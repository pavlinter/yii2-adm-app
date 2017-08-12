<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Alert renders an alert bootstrap component.
 *
 * For example,
 *
 * ```php
 * echo Note::widget([
 *     'type' => Note::TYPE_SUCCESS,
 *     'options' => [
 *         'class' => 'my-class',
 *     ],
 *     'body' => 'Say hello...',
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the alert box:
 *
 * ```php
 * Note::begin([
 *     'type' => Note::TYPE_SUCCESS,
 *     'options' => [
 *         'class' => 'my-class',
 *     ],
 * ]);
 *
 * echo 'Say hello...';
 *
 * Note::end();
 * ```
 *
 */
class Note extends \yii\base\Widget
{
    const TYPE_DEFAULT = '';
    const TYPE_SUCCESS = 'note-success';
    const TYPE_DANGER = 'note-danger';
    const TYPE_INFO = 'note-info';
    const TYPE_WARNING = 'note-warning';

    public $options = [];

    public $type = self::TYPE_DEFAULT;
    /**
     * @var string the body content in the alert component. Note that anything between
     * the [[begin()]] and [[end()]] calls of the Alert widget will also be treated
     * as the body content, and will be rendered before this.
     */
    public $body;



    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . Html::endTag('div');
    }


    /**
     * Renders the alert body (if any).
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return $this->body . "\n";
    }


    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        Html::addCssClass($this->options, ['note', $this->type]);
    }
}
