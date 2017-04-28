<?php

/**
 * @package yii2-adm
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @copyright Copyright &copy; Pavels Radajevs <pavlinter@gmail.com>, 2015
 * @version 1.0.9
 */

namespace app\modules\admgii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The action view file path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    public $admAuto;
    public $modelClass;
    public $moduleID;
    public $controllerClass;
    public $baseControllerClass = 'yii\web\Controller';
    public $indexWidgetType = 'admGrid';
    public $searchModelClass = '';
    public $enableLanguage = true;
    public $languagePanelType = 'panelTab';
    public $enableI18N = true;
    public $template = 'adm';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->templates['adm'])) {
            $this->templates['adm'] = '@admgii/generators/crud/adm';
        }
        if (!isset($this->templates['frontend'])) {
            $this->templates['frontend'] = '@admgii/generators/crud/frontend';
        }
        $this->templates['default'] = Yii::getAlias('@vendor/yiisoft/yii2-gii/generators/crud/default');
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['moduleID', 'controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'indexWidgetType'], 'required'],
            [['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
            [['controllerClass'], 'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/', 'message' => 'Controller class name must start with an uppercase letter.'],
            [['controllerClass', 'searchModelClass'], 'validateNewClass'],
            [['indexWidgetType'], 'in', 'range' => ['grid', 'list','admGrid']],
            [['languagePanelType'], 'in', 'range' => ['panelTab', 'panelToggle']],
            [['modelClass'], 'validateModelClass'],
            [['moduleID'], 'validateModuleID'],
            [['enableI18N','enableLanguage'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'moduleID' => 'Module ID',
            'controllerClass' => 'Controller Class',
            'baseControllerClass' => 'Base Controller Class',
            'indexWidgetType' => 'Widget Used in Index Page',
            'searchModelClass' => 'Search Model Class',
            'enableLanguage' => 'Enable Language CRUD',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase with an uppercase first letter. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
                You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
            'moduleID' => 'This is the ID of the module that the generated controller will belong to.
                If not set, it means the controller will belong to the application.',
            'indexWidgetType' => 'This is the widget type to be used in the index page to display list of the models.
                You may choose either <code>GridView</code> or <code>ListView</code>',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a fully
                qualified namespaced class name, e.g., <code>app\models\PostSearch</code>.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass', 'moduleID', 'indexWidgetType']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();
        if (empty($pk)) {
            $this->addError('modelClass', "The table associated with $class must have primary key(s).");
        }
    }

    /**
     * Checks if model ID is valid
     */
    public function validateModuleID()
    {
        if (!empty($this->moduleID)) {
            $module = Yii::$app->getModule($this->moduleID);
            if ($module === null) {
                $this->addError('moduleID', "Module '{$this->moduleID}' does not exist.");
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class);
    }

    /**
     * @return string the action view file path
     */
    public function getViewPath()
    {
        $module = empty($this->moduleID) ? Yii::$app : Yii::$app->getModule($this->moduleID);

        return $module->getViewPath() . '/' . $this->getControllerID() ;
    }


    /**
     * @return int|null|string
     */
    public function getNameComment()
    {
        /* @var $class \yii\db\ActiveRecord */
        $class = new $this->modelClass;
        $schema = $class::getTableSchema();

        foreach ($schema->columns as $name => $column) {
            if($column->comment === 'name'){
                return $name;
            }
        }

        $behaviors = $class->getBehaviors();
        foreach ($behaviors as $behaviorName => $behavior) {
            if($behavior instanceof \pavlinter\translation\TranslationBehavior){
                $relation = $behavior->relation;
                $method = "get" . ucfirst($relation);
                if ($class->hasMethod($method)) {
                    /* @var $class \yii\db\ActiveQuery */
                    $query = $class->{$method}();
                    $classLang = $query->modelClass;
                    $schema = $classLang::getTableSchema();
                    foreach ($schema->columns as $name => $column) {
                        if($column->comment === 'name'){
                            return $name;
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        $name = $this->getNameComment();
        if ($name !== null) {
            return $name;
        }

        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = new $this->modelClass;
        $behaviors = $class->getBehaviors();
        foreach ($behaviors as $behaviorName => $behavior) {
            if($behavior instanceof \pavlinter\translation\TranslationBehavior){
                foreach (['name', 'title'] as $name) {
                    if (in_array($name, $behavior->translationAttributes)) {
                        return $name;
                    }
                }

            }
        }
        $pk = $class::primaryKey();
        return $pk[0];
    }

    /**
     * @return bool
     */
    public function hasBreadcrumbsTree()
    {
        /* @var $class \yii\db\ActiveRecord */
        $class = new $this->modelClass;
        return $class->hasMethod('breadcrumbsTree');
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute, $options = [])
    {
        $fix = ArrayHelper::remove($options, 'fix', false);
        $field = $this->getFieldType([
            'attribute' => $attribute,
            'model' => $this,
            'modelStr' => "\$model",
            'attributeStr' => "'$attribute'",
            'fix' => $fix,
        ]);
        if(!$fix){
            return $field;
        }

        $options = [
            'class' => 'col-xs-12 col-sm-6 col-md-3',
        ];

        $tableSchema = $this->getTableSchema();
        if ($tableSchema !== false && isset($tableSchema->columns[$attribute])) {
            $column = $tableSchema->columns[$attribute];
            if ($column->comment == 'checkbox'){
                Html::addCssClass($options, 'form-without-label');
            }
        }
        return Html::tag('div', "\n\t\t\t<?= " . $field . " ?>\n\t\t", $options) . "\n\t\t";
    }
    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveFieldLang($attribute)
    {
        $class = new $this->modelClass();
        $langClass = $class->getOneTranslation(Yii::$app->getI18n()->getId());
        return $this->getFieldType([
            'attribute' => $attribute,
            'model' => $langClass,
            'modelStr' => "\$model->getOneTranslation(\$id_language)",
            'attributeStr' => "'['.\$id_language.']$attribute'",
            'lang' => true
        ]);


    }

    public function getFieldType($params)
    {
        $params = ArrayHelper::merge([
            'lang' => false,
            'bsCol' => false,
        ], $params);
        /* @var $attribute string */
        /* @var $model \yii\db\ActiveRecord */
        /* @var $modelStr string */
        /* @var $attributeStr string */
        /* @var $lang bool */
        /* @var $fix bool */
        extract($params);

        $field = "\$form->field(" . $modelStr . ", " . $attributeStr . ")";

        $tableSchema = $model->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return $field."->passwordInput()";
            } else {
                return $field;
            }
        }
        $column = $tableSchema->columns[$attribute];

        if ($lang) {
            $t = "\t\t\t\t\t";
            $t2 = "\t\t\t\t\t";
            $t3 = "\t\t\t\t\t\t";
        } else if($fix) {
            $t  = "\t\t\t\t";
            $t2 = "\t\t\t";
            $t3 = "\t\t\t\t";
        } else {
            $t  = "\t\t";
            $t2 = "\t";
            $t3 = "\t\t";
        }

        $column->comment = strtolower($column->comment);

        if ($column->comment == 'redactor'){
            return "\\pavlinter\\adm\\Adm::widget('Redactor',[\n$t3'form' => \$form,\n$t3'model'      => " . $modelStr . ",\n$t3'attribute'  => " . $attributeStr . "\n$t2])";
        }
        if ($column->comment == 'fileinput'){
            return "\\pavlinter\\adm\\Adm::widget('FileInput',[\n$t3'form'        => \$form,\n$t3'model'       => " . $modelStr . ",\n$t3'attribute'   => " . $attributeStr . "\n$t2])";
        }

        if ($column->comment == 'checkbox'){
            return "\$form->field(" . $modelStr . ", " . $attributeStr . ", [\"template\" => \"{input}\\n{label}\\n{hint}\\n{error}\"])->widget(\\kartik\\checkbox\\CheckboxX::classname(), [\n$t3'pluginOptions' => [\n\t$t3'threeState' => false\n$t3]\n$t2]);";
        }

        if ($column->comment == 'select2' || $column->comment == 'range' || $column->dbType === 'tinyint(1)'){
            if ($column->comment == 'range' || $column->dbType === 'tinyint(1)') {
                $data = "\$model::{$column->name}_list()";
            } else {
                $data = '[]';
            }

            return $field."->widget(\\kartik\\widgets\\Select2::classname(), [\n$t3'data' => $data,\n$t3'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],\n$t3'pluginOptions' => [\n\t$t3'allowClear' => true,\n$t3]\n$t2]);";
        }



        if ($column->phpType === 'boolean' || $column->phpType === 'tinyint(1)') {
            return $field."->checkbox()";
        } elseif ($column->type === 'text' || $column->comment == 'textarea') {
            return $field."->textarea(['rows' => 6])";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return $field."->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return $field."->$input()";
            } else {
                return $field."->$input(['maxlength' => $column->size])";
            }
        }
    }
    
    
    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } else {
            return "\$form->field(\$model, '$attribute')";
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes()
    {
        return $this->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels()
    {
        /* @var $model \yii\base\Model */
        $model = new $this->modelClass();
        $attributeLabels = $model->attributeLabels();
        $labels = [];
        foreach ($this->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeConditions[] = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\$model->$pk";
                } else {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function generateActionParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            return '$id';
        } else {
            return '$' . implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (substr(strtolower($pk), -2) == 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
            }

            return $params;
        }
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return boolean|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }


    /**
     * @param $column
     * @param array $props
     * @return bool
     */
    public function checkCol($column, $props = [])
    {
        $tableSchema = $this->getTableSchema();
        if($tableSchema){
            if(isset($tableSchema->columns[$column])){
                if($props){
                    $c = $tableSchema->columns[$column];
                    $valid = true;
                    foreach ($props as $prop => $v) {
                        if($c->hasProperty($prop)){
                            $valid = $valid && ($c->$prop == $v);
                        } else {
                            $valid = false;
                        }
                    }
                    return $valid;
                }
                return true;
            }
        }
        return false;
    }


    /**
     * @param $column
     * @param array $props
     * @return bool
     */
    public function getParentColumn()
    {
        static $parent = null;
        if($parent === null){
            $parent = false;
            $tableSchema = $this->getTableSchema();
            if($tableSchema){
                foreach ($tableSchema->columns as $column) {
                    if($column->comment == 'parent' || $column->comment == 'id_parent' || $column->comment == 'parent_id'){
                        $parent = $column->name;
                        break;
                    }
                }
            }
        }
        return $parent;
    }


    /**
     * @param $if
     * @param string $else
     * @return string
     */
    public function ifParent($if, $else = '')
    {
        if($this->getParentColumn())
        {
            return $if;
        }
        return $else;
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();

            return $model->attributes();
        }
    }
    /**
     * Generates a string depending on enableI18N property
     *
     * @param string $string the text be generated
     * @param array $placeholders the placeholders to use by `Yii::t()`
     * @return string
     */
    public function generateString($string = '', $placeholders = [])
    {
        $subcategory = ArrayHelper::remove($placeholders, 'subcategory');
        $dropN = ArrayHelper::remove($placeholders, 'dropN');

        $string = addslashes($string);
        if ($this->enableI18N) {
            // If there are placeholders, use them
            if (!empty($placeholders)) {
                if($dropN){
                    $ph = ltrim(VarDumper::export($placeholders), '[');
                    $ph = $ph = ', [' . trim(str_replace("\n", '', $ph));
                } else {
                    $ph = ', ' . VarDumper::export($placeholders);
                }
            } else {
                $ph = '';
            }



            $messageCategory = $this->messageCategory;
            if($subcategory && ($pos = strpos($messageCategory, '/')) !== false){
                $messageCategory = substr($messageCategory, 0, $pos + 1) . $subcategory;
            }

            /*if ($this->template === 'adm') {
                if(strpos($this->messageCategory,'adm/') === 0){
                    $this->messageCategory = substr($this->messageCategory, 4, strlen($this->messageCategory));
                }
                $str = "Adm::t('" . $this->messageCategory . "', '" . $string . "'" . $ph . ")";
            }*/
            $str = "Yii::t('" . $messageCategory . "', '" . $string . "'" . $ph . ")";
        } else {
            // No I18N, replace placeholders by real words, if any
            if (!empty($placeholders)) {
                $phKeys = array_map(function($word) {
                    return '{' . $word . '}';
                }, array_keys($placeholders));
                $phValues = array_values($placeholders);
                $str = "'" . str_replace($phKeys, $phValues, $string) . "'";
            } else {
                // No placeholders, just the given string
                $str = "'" . $string . "'";
            }
        }
        return $str;
    }

    /**
     * @param $column
     * @return string
     */
    public function generateColumn($column)
    {
        $return = "";
        if (is_object($column)) {
            $format = $this->generateColumnFormat($column);
            $return .= "\t\t\t[\n";
            if($this->checkCol($column->name, ['comment' => 'range'])){
                $return .= "\t\t\t\t'attribute' => '". $column->name ."',\n";
                $return .= "\t\t\t\t'vAlign' => 'middle',\n";
                $return .= "\t\t\t\t'hAlign' => 'center',\n";
                $return .= "\t\t\t\t'format' => '". $format ."',\n";
                $return .= "\t\t\t\t'filter' => \$searchModel::" . $column->name . "_list(),\n";
                $return .= "\t\t\t\t'value' => function (\$model) {\n";
                $return .= "\t\t\t\t\treturn \$model::" . $column->name . "_list(\$model->" . $column->name . ");\n";
                $return .= "\t\t\t\t},\n";
                $return .= "\t\t\t\t'filterType' => \kartik\grid\GridView::FILTER_SELECT2,\n";
                $return .= "\t\t\t\t'filterWidgetOptions' => [\n";
                $return .= "\t\t\t\t\t'pluginOptions'=> [\n";
                $return .= "\t\t\t\t\t\t'allowClear' => true\n";
                $return .= "\t\t\t\t\t],\n";
                $return .= "\t\t\t\t],\n";
                $return .= "\t\t\t\t'filterInputOptions' => [\n";
                $return .= "\t\t\t\t\t'placeholder' => Adm::t('','Select ...', ['dot' => false])\n";
                $return .= "\t\t\t\t],\n";
            } elseif($this->checkCol($column->name, ['comment' => 'checkbox'])){
                $return .= "\t\t\t\t'class' => 'app\\widgets\\BooleanColumn',\n";
                $return .= "\t\t\t\t'attribute' => '". $column->name ."',\n";
                $return .= "\t\t\t\t'tableName' => \$searchModel::tableName(),\n";
            } else {
                $return .= "\t\t\t\t'attribute' => '". $column->name ."',\n";
                $return .= "\t\t\t\t'vAlign' => 'middle',\n";
                $return .= "\t\t\t\t'hAlign' => 'center',\n";
                if($column->name == 'id'){
                    $return .= "\t\t\t\t'width' => '70px',\n";
                }
                $return .= "\t\t\t\t'format' => '". $format ."',\n";
            }

            $return .= "\t\t\t],\n";
        } else {
            $return .= "\t\t\t[\n";
            $return .= "\t\t\t\t'attribute' => '". $column ."',\n";
            $return .= "\t\t\t\t'vAlign' => 'middle',\n";
            $return .= "\t\t\t\t'hAlign' => 'center',\n";
            if($column == 'id'){
                $return .= "\t\t\t\t'width' => '70px',\n";
            }
            $return .= "\t\t\t\t'format' => 'text',\n";
            $return .= "\t\t\t],\n";
        }
        return $return;
    }


    /**
     * @param $column
     * @return string
     */
    public function generateColumnDetailView($column)
    {
        $return = "";
        if (is_object($column)) {
            $format = $this->generateColumnFormat($column);
            $return .= "\t\t\t[\n";
            $return .= "\t\t\t\t'attribute' => '". $column->name ."',\n";
            $return .= "\t\t\t\t'format' => '". $format ."',\n";
            $return .= "\t\t\t],\n";
        } else {
            $return .= "\t\t\t[\n";
            $return .= "\t\t\t\t'attribute' => '". $column ."',\n";
            $return .= "\t\t\t\t'format' => 'text',\n";
            $return .= "\t\t\t],\n";
        }
        return $return;
    }

    /**
     * @param $name
     * @return string
     */
    public function generateColumnTrans($name)
    {
        $return = "";
        $return .= "\t\t\t[\n";
        $return .= "\t\t\t\t'attribute' => '". $name ."',\n";
        $return .= "\t\t\t\t'format' => 'text',\n";
        $return .= "\t\t\t],\n";
        return $return;
    }
}
