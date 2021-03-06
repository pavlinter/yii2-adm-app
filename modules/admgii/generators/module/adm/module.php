<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator app\modules\admgii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?= $ns ?>;

use Yii;
use pavlinter\adm\Adm;
use pavlinter\adm\AdmBootstrapInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class Module
 * @package <?= $ns ?>
 */
class <?= $className ?> extends \yii\base\Module implements AdmBootstrapInterface
{
    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();
        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

    /**
     * @param \pavlinter\adm\Adm $adm
     */
    public function loading($adm)
    {
        $adm->params['left-menu']['<?= $generator->moduleID ?>'] = [
            'label' => '<i class="fa fa-hdd-o"></i><span>' . static::t('', '<?= $generator->moduleID ?>') . '</span>',
            'url' => ['/<?= $generator->moduleID ?>/default/index']
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            //throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            return false;
        }

        //if ($action->controller->id !== 'default') {
        $adm = Adm::register(); //required load adm,if use adm layout
        if(!($adm->user->can('AdmRoot') || $adm->user->can('AdmAdmin'))){
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }
        //}
        return true;
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['<?= $generator->moduleID ?>*'])) {
            Yii::$app->i18n->translations['<?= $generator->moduleID ?>*'] = [
                'class' => 'pavlinter\translation\DbMessageSource',
                'forceTranslation' => true,
                'autoInsert' => true,
                'dotMode' => true,
            ];
        }
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if ($category) {
            $category = '<?= $generator->moduleID ?>/' . $category;
        } else {
            $category = '<?= $generator->moduleID ?>';
        }
        return Yii::t($category, $message, $params, $language);
    }

    /**
     * @return self
     */
    public static function getInst()
    {
        return Yii::$app->getModule('<?= $generator->moduleID ?>');
    }
}
