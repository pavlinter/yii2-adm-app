<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator \app\modules\admgii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
/* @var $modelLangClass \yii\db\ActiveRecord */
$modelLangClassName = \yii\helpers\StringHelper::basename($generator->modelLangClass);
$haveWeight = $generator->haveWeight($tableSchema->columns);

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

<?php if ($generator->isLang || $modelLangClassName){ ?>
use app\core\adm\models\Language;
<?php } ?>
<?php if ($generator->modelClassQueryUse){ ?>
use <?= $generator->modelClassQueryUse ?>;
<?php } ?>
use yii\helpers\ArrayHelper;
use app\helpers\Url;
use app\base\ModelArrayableTrait;
use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php if ($generator->modelLangClass): ?>
 * @method \pavlinter\translation\TranslationBehavior getLangModels
 * @method \pavlinter\translation\TranslationBehavior setLanguage
 * @method \pavlinter\translation\TranslationBehavior getLanguage
 * @method \pavlinter\translation\TranslationBehavior saveTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAllTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAll
 * @method \pavlinter\translation\TranslationBehavior validateAll
 * @method \pavlinter\translation\TranslationBehavior validateLangs
 * @method \pavlinter\translation\TranslationBehavior loadAll
 * @method \pavlinter\translation\TranslationBehavior loadLang
 * @method \pavlinter\translation\TranslationBehavior loadLangs
 * @method \pavlinter\translation\TranslationBehavior loadTranslations
 * @method \pavlinter\translation\TranslationBehavior getOneTranslation
 * @method \pavlinter\translation\TranslationBehavior hasTranslation
 *
<?php endif; ?>
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if ($generator->modelLangClass){ ?>
 *
 * Translations
<?php
    $modelLangClassObj  = new $generator->modelLangClass();
    foreach ($modelLangClassObj->attributes() as $attribute){
        if($attribute == 'id' || preg_match('#^id_#i', $attribute) || preg_match('#_id$#i', $attribute)){
            continue;
        } ?>
 * @property string $<?= $attribute;?>

<?php } ?>
<?php } ?>
<?php if (!empty($relations)): ?>
 *
 * Relations
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . ($name == $modelLangClassName.'s'? 'translations' : lcfirst($name) ) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    use ModelArrayableTrait;

    /**
     * @inheritdoc
     *
     * The default implementation returns the names of the columns whose values have been populated into this record.
     */
    public function fields()
    {
        $fields =  $this->traitFields();

        return $fields;
    }

<?php if ($generator->modelClassQuery): ?>
    /**
     * @inheritdoc
     * @return <?= $className ?>Query
     */
    public static function find()
    {
        return new <?= $className ?>Query(get_called_class());
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?= $generator->timestampBehavior($tableSchema->columns) ?>
<?php if ($generator->modelLangClass){ ?>
            'trans' => [
                'class' => \pavlinter\translation\TranslationBehavior::class,
                'translationAttributes' => [
<?php
$modelLangClassObj  = new $generator->modelLangClass();
foreach ($modelLangClassObj->attributes() as $attribute){
    if($attribute == 'id' || preg_match('#^id_#i', $attribute) || preg_match('#_id$#i', $attribute)){
        continue;
    }
?>
                    '<?= $attribute;?>',
<?php } ?>
                ]
            ],
<?php } ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['category_id', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
<?php if ($generator->getParentColumn()) {?>
            ['<?= $generator->getParentColumn() ?>', 'exist', 'targetAttribute' => 'id'],
<?php }?><?= "            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        //$scenarios['client-create'] = <?= $generator->generateScenarioColumn($tableSchema) ?>;
        //$scenarios['client-update'] = <?= $generator->generateScenarioColumn($tableSchema) ?>;
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php if ($haveWeight !== false) {?>

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (empty($this-><?= $haveWeight ?>)) {
            $query = static::find()->select(['MAX(<?= $haveWeight ?>)']);
            if (!$insert) {
                $query->where(['!=', 'id', $this->id]);
            }
            $this-><?= $haveWeight ?> = $query->scalar() + 50;
        }
        return parent::beforeSave($insert);
    }

<?php }?>

    /**
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->display->removeFileRow('<?= strtolower($className) ?>', $this->id);
            return true;
        }
        return false;
    }
<?php if ($generator->isLang){ ?>

    /**
     * @param $url
     * @param string $key
     * @return mixed
     */
    /* public function url($url = [], $key = 'alias')
    {
        if ($this->url) {
            return $this->url;
        }
        if ($key) {
            $url[$key] = $this->alias;
        }
        return $url;
    } */

    /**
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    /* public function urlTo($scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'url' => [],
            'key' => 'alias',
        ], $options);
        return Url::to($this->url($options['url'], $options['key']), $scheme);
    } */
<?php } else {?>

    /**
     * @param $url
     * @param null $id_language
     * @param string $key
     * @return mixed
     */
    /* public function url($url = true, $id_language = null, $key = 'alias')
    {
        if ($url === true) {
            $url = ['/admpages/default/index'];
        }
        return $this->getOneTranslation($id_language)->url($url, $key);
    } */

    /**
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    /* public function urlTo($scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'url' => true,
            'id_language' => null,
            'key' => 'alias',
        ], $options);
        return Url::to($this->url($options['url'], $options['id_language'], $options['key']), $scheme);
    } */
<?php }?>
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name; ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($generator->getParentColumn()) {?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(static::class, ['<?= $generator->getParentColumn() ?>' => 'id']);
    }
<?php }?>
<?php foreach ($generator->rangeColumn as $column): ?>

    /**
     * @param mixed $key
     * @param null $default
     * @return array|null
     */
    public static function <?= $column ?>_list($key = false, $default = null)
    {
        $list = [
            0 => <?= $generator->generateString('0', [], $column) ?>,
            1 => <?= $generator->generateString('1', [], $column) ?>,
        ];
        if ($key !== false) {
            if (isset($list[$key])) {
                return $list[$key];
            }
            return $default;
        }
        return $list;
    }
<?php endforeach; ?>
<?php if ($generator->getParentColumn()) {?>

    /**
     * @param $breadcrumbs
     * @param null $id_parent
     * @param array $options
     */
    public static function breadcrumbsTree(&$breadcrumbs, $id_parent = null, $options = [])
    {
        if (!$id_parent) {
            return;
        }
        $options = ArrayHelper::merge([
            'level' => ArrayHelper::remove($options, 'level', 0),
            'lastLink' => false,
            'url' => ArrayHelper::remove($options, 'url', ['index']),
        ], $options);
        $options['level']++;

        $model = static::find()->where(['id' => $id_parent])->one();
        if($model !== null){
            $id_parent = $model->id;
            if($id_parent == null){
                $id_parent = 0;
            }
            $url = ArrayHelper::merge($options['url'], ['id_parent' => $id_parent]);
            if ($options['level'] == 1 && !$options['lastLink']) {
                $item = $model-><?= $generator->getNameAttribute() ?>;
            } else {
                $item = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => $url];
            }
            if($breadcrumbs){
                array_unshift($breadcrumbs, $item);
            } else {
                $breadcrumbs = [$item];
            }
            static::breadcrumbsTree($breadcrumbs, $model-><?= $generator->getParentColumn() ?>, $options);
        }
    }
<?php }?>
<?php if (!$generator->isLang){ ?>
    /**
     * @param bool $exception
     * @return bool
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkOwn($exception = true)
    {
        if ($exception) {
            if ($this->user_id !== Yii::$app->user->getId()) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page.');
            }
        } else {
            if ($this->user_id !== Yii::$app->user->getId()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $status
     * @param bool $exception
     * @return bool
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkStatus($status , $exception = true)
    {
        $status = (array)$status;
        if ($exception) {
            if (!in_array($this->status, $status)) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed.');
            }
        } else {
            if (!in_array($this->status, $status)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param bool $exception
     * @return bool
     * @throws \yii\web\NotFoundHttpException
     */
    public function checkActive($exception = true)
    {
        if ($exception) {
            if (!$this->active) {
                throw new \yii\web\NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
        } else {
            if (!$this->active) {
                return false;
            }
        }
        return true;
    }
<?php }?>
}
