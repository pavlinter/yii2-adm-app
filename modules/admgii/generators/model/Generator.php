<?php

namespace app\modules\admgii\generators\model;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Schema;
use app\modules\admgii\CodeFile;
use yii\helpers\Inflector;
use yii\base\NotSupportedException;
use yii\helpers\StringHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \app\modules\admgii\Generator
{
    public $db = 'db';
    public $ns = 'app\modules\appadm\models';
    public $nsMiddle = 'app\models';
    public $tableName;
    public $modelClass;
    public $modelClassQuery = true;
    public $modelClassQueryNs; //private
    public $modelClassQueryUse; //private
    public $modelLangClass;
    public $middleModelClass;
    public $baseClass = 'yii\db\ActiveRecord';
    public $generateRelations = true;
    public $generateLabelsFromComments = false;
    public $useTablePrefix = true;

    public $template = 'adm';
    public $enableI18N = true;
    public $isLang = false;
    public $messageCategory = 'model/';

    public $rangeColumn = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->templates['adm'])) {
            $this->templates['adm'] = '@admgii/generators/model/adm';
        }
        $this->templates['default'] = Yii::getAlias('@vendor/yiisoft/yii2-gii/generators/model/default');
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Model Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['db', 'ns', 'tableName', 'modelClass', 'baseClass'], 'filter', 'filter' => 'trim'],
            [['ns', 'modelLangClass'], 'filter', 'filter' => function($value) { return trim($value, '\\'); }],
            [['modelLangClass'], 'filter', 'filter' => function($value) {
                if($value !== '' && strpos($value, '\\') === false){
                    return $this->ns . '\\' . $value;
                }
                return $value;
            }],
            [['db', 'ns', 'tableName', 'baseClass'], 'required'],
            [['db', 'modelClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['ns', 'baseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['tableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
            [['db'], 'validateDb'],
            [['ns'], 'validateNamespace'],
            [['tableName'], 'validateTableName'],
            [['modelClass'], 'validateModelClass', 'skipOnEmpty' => false],
            [['modelLangClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::class]],
            [['baseClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::class]],
            [['generateRelations', 'generateLabelsFromComments', 'middleModelClass'], 'boolean'],
            [['modelClassQuery'], 'boolean'],
            [['enableI18N'], 'boolean'],
            [['useTablePrefix'], 'boolean'],
            [['isLang'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ns' => 'Namespace',
            'db' => 'Database Connection ID',
            'tableName' => 'Table Name',
            'modelClass' => 'Model Class',
            'middleModelClass' => 'Generate Middle Model',
            'modelLangClass' => 'Model Lang Class',
            'isLang' => 'It is language model',
            'baseClass' => 'Base Class',
            'generateRelations' => 'Generate Relations',
            'generateLabelsFromComments' => 'Generate Labels from DB Comments',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'ns' => 'This is the namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>',
            'db' => 'This is the ID of the DB application component.',
            'tableName' => 'This is the name of the DB table that the new ActiveRecord class is associated with, e.g. <code>post</code>.
                The table name may consist of the DB schema part if needed, e.g. <code>public.post</code>.
                The table name may end with asterisk to match multiple table names, e.g. <code>tbl_*</code>
                will match tables who name starts with <code>tbl_</code>. In this case, multiple ActiveRecord classes
                will be generated, one for each matching table name; and the class names will be generated from
                the matching characters. For example, table <code>tbl_post</code> will generate <code>Post</code>
                class.',
            'modelClass' => 'This is the name of the ActiveRecord class to be generated. The class name should not contain
                the namespace part as it is specified in "Namespace". You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case multiple ActiveRecord classes will be generated.',
            'modelLangClass' => 'example: app\models\PageLang OR PageLang (press enter)',
            'baseClass' => 'This is the base class of the new ActiveRecord class. It should be a fully qualified namespaced class name.',
            'generateRelations' => 'This indicates whether the generator should generate relations based on
                foreign key constraints it detects in the database. Note that if your database contains too many tables,
                you may want to uncheck this option to accelerate the code generation process.',
            'generateLabelsFromComments' => 'This indicates whether the generator should generate attribute labels
                by using the comments of the corresponding DB columns.',
            'useTablePrefix' => 'This indicates whether the table name returned by the generated ActiveRecord class
                should consider the <code>tablePrefix</code> setting of the DB connection. For example, if the
                table name is <code>tbl_post</code> and <code>tablePrefix=tbl_</code>, the ActiveRecord class
                will return the table name as <code>{{%post}}</code>.',
            'middleModelClass' => $this->nsMiddle . '\...',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function autoCompleteData()
    {
        $db = $this->getDbConnection();
        if ($db !== null) {
            return [
                'tableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
            ];
        } else {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['model.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['ns', 'db', 'baseClass', 'generateRelations', 'generateLabelsFromComments']);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);

            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$className]) ? $relations[$className] : [],
                'appadmClass' => $this->ns . '\\' . $className,
            ];

            if ($this->middleModelClass) {
                $this->modelClassQueryNs = $this->nsMiddle . '\query';
            } else {
                $this->modelClassQueryNs = $this->ns . '\query';
            }

            $this->modelClassQueryUse = $this->modelClassQueryNs . '\\' . $className . 'Query';


            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php',
                $this->render('model.php', $params)
            );

            if ($this->middleModelClass) {
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->nsMiddle)) . '/' . $className . '.php',
                    $this->render('middleModel2.php', $params)
                );
            }

            if ($this->modelClassQuery) {
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->modelClassQueryUse)) . '.php',
                    $this->render('modelScopes.php', $params)
                );
            }




        }

        return $files;
    }

    /**
     * Generates the attribute labels for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated attribute labels (name => label)
     */
    public function generateLabels($table)
    {
        $labels = [];
        foreach ($table->columns as $column) {
            if ($this->generateLabelsFromComments && !empty($this->getFieldComment($column, true))) {
                $labels[$column->name] = $this->getFieldComment($column, true);
            } elseif (!strcasecmp($column->name, 'id')) {
                $labels[$column->name] = 'ID';
            } else {
                $label = Inflector::camel2words($column->name);
                if(!empty($label)){
                    if (substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3);
                    } elseif(substr_compare($label, 'id ', 0, 3, true) === 0) {
                        $label = substr($label, 3);
                    }elseif(substr_compare($label, ' at', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3);
                    }
                }

                $labels[$column->name] = $label;
            }
        }

        return $labels;
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $types = [];
        $lengths = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }


            if (!$column->allowNull && $column->defaultValue === null) {
                if($this->isLang && preg_match("/_id$/", $column->name)){

                } else {
                    $types['required'][] = $column->name;
                }
            }
            $type = $column->type;

            if($this->getFieldComment($column) == 'checkbox'){
                $type = Schema::TYPE_BOOLEAN;
            }

            if($this->getFieldComment($column) == 'range'){
                $type = 'range';
            }

            switch ($type) {
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case 'range':
                case Schema::TYPE_SMALLINT:
                    $this->rangeColumn[$column->name] = $column->name;
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
                    if($this->getFieldComment($column) !== 'created_at' && $this->getFieldComment($column) !== 'updated_at'){
                        $types['safe'][] = $column->name;
                    }
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }
        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        foreach ($this->rangeColumn as $column) {
            $rules[] = "['" . $column . "', 'in', 'range' => array_keys(static::" . $column . "_list())]";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }

        // Unique indexes rules
        try {
            $db = $this->getDbConnection();
            $uniqueIndexes = $db->getSchema()->findUniqueIndexes($table);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount == 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $labels = array_intersect_key($this->generateLabels($table), array_flip($uniqueColumns));
                        $lastLabel = array_pop($labels);
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['" . $columnsList . "'], 'unique', 'targetAttribute' => ['" . $columnsList . "'], 'message' => 'The combination of " . implode(', ', $labels) . " and " . $lastLabel . " has already been taken.']";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        return $rules;
    }

    /**
     * @param $tableSchema yii\db\TableSchema
     * @return string
     */
    public function generateScenarioColumn($tableSchema)
    {
        $fields = [];
        foreach ($tableSchema->columns as $column) {
            if($column->autoIncrement || in_array($this->getFieldComment($column), ['weight', 'created_at', 'updated_at'])){
                continue;
            }
            $fields[] = $column->name;
        }

        if ($fields) {
            return "['" . implode("', '", $fields) . "']";
        }
        return '[]';
    }

    /**
     * @return array the generated relation declarations
     */
    protected function generateRelations()
    {
        if (!$this->generateRelations) {
            return [];
        }

        $db = $this->getDbConnection();

        if (($pos = strpos($this->tableName, '.')) !== false) {
            $schemaName = substr($this->tableName, 0, $pos);
        } else {
            $schemaName = '';
        }

        $relations = [];

        $modelLangClass = StringHelper::basename($this->modelLangClass);

        foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
            $tableName = $table->name;
            $className = $this->generateClassName($tableName);
            foreach ($table->foreignKeys as $refs) {
                $refTable = $refs[0];
                unset($refs[0]);
                $fks = array_keys($refs);
                $refClassName = $this->generateClassName($refTable);

                // Add relation for this table
                $link = $this->generateRelationLink(array_flip($refs));
                $relationName = $this->generateRelationName($relations, $className, $table, $fks[0], false);



                $relations[$className][$relationName] = [
                    "return \$this->hasOne($refClassName::class, $link);",
                    $refClassName,
                    false,
                ];



                // Add relation for the referenced table
                $hasMany = false;
                if (count($table->primaryKey) > count($fks)) {
                    $hasMany = true;
                } else {
                    foreach ($fks as $key) {
                        if (!in_array($key, $table->primaryKey, true)) {
                            $hasMany = true;
                            break;
                        }
                    }
                }
                $link = $this->generateRelationLink($refs);
                $relationName = $this->generateRelationName($relations, $refClassName, $refTable, $className, $hasMany);
                if ($className == $modelLangClass) {
                    $relations[$refClassName]['Translation'] = [
                        "return \$this->hasOne($className::class, $link)->andWhere(['language_id' => Yii::\$app->i18n->getId()]);",
                        $className,
                        false,
                    ];
                    $relations[$refClassName]['Translations'] = [
                        "return \$this->hasMany($className::class, $link);",
                        $className,
                        true,
                    ];
                    /*echo '<pre>';
                    echo print_r($relations[$refClassName]);
                    echo '</pre>';
                    exit();*/

                } else {
                    $relations[$refClassName][$relationName] = [
                        "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($className::class, $link);",
                        $className,
                        $hasMany,
                    ];
                }







            }



            if (($fks = $this->checkPivotTable($table)) === false) {
                continue;
            }
            $table0 = $fks[$table->primaryKey[0]][0];
            $table1 = $fks[$table->primaryKey[1]][0];
            $className0 = $this->generateClassName($table0);
            $className1 = $this->generateClassName($table1);

            $link = $this->generateRelationLink([$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]]);
            $viaLink = $this->generateRelationLink([$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]]);
            $relationName = $this->generateRelationName($relations, $className0, $db->getTableSchema($table0), $table->primaryKey[1], true);
            $relations[$className0][$relationName] = [
                "return \$this->hasMany($className1::class, $link)->viaTable('" . $this->generateTableName($table->name) . "', $viaLink);",
                $className1,
                true,
            ];

            $link = $this->generateRelationLink([$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]]);
            $viaLink = $this->generateRelationLink([$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]]);
            $relationName = $this->generateRelationName($relations, $className1, $db->getTableSchema($table1), $table->primaryKey[0], true);
            $relations[$className1][$relationName] = [
                "return \$this->hasMany($className0::class, $link)->viaTable('" . $this->generateTableName($table->name) . "', $viaLink);",
                $className0,
                true,
            ];
        }
        return $relations;
    }

    /**
     * Generates the link parameter to be used in generating the relation declaration.
     * @param array $refs reference constraint
     * @return string the generated link parameter.
     */
    protected function generateRelationLink($refs)
    {
        $pairs = [];
        foreach ($refs as $a => $b) {
            $pairs[] = "'$a' => '$b'";
        }

        return '[' . implode(', ', $pairs) . ']';
    }

    /**
     * Checks if the given table is a junction table.
     * For simplicity, this method only deals with the case where the pivot contains two PK columns,
     * each referencing a column in a different table.
     * @param \yii\db\TableSchema the table being checked
     * @return array|boolean the relevant foreign key constraint information if the table is a junction table,
     * or false if the table is not a junction table.
     */
    protected function checkPivotTable($table)
    {
        $pk = $table->primaryKey;
        if (count($pk) !== 2) {
            return false;
        }
        $fks = [];
        foreach ($table->foreignKeys as $refs) {
            if (count($refs) === 2) {
                if (isset($refs[$pk[0]])) {
                    $fks[$pk[0]] = [$refs[0], $refs[$pk[0]]];
                } elseif (isset($refs[$pk[1]])) {
                    $fks[$pk[1]] = [$refs[0], $refs[$pk[1]]];
                }
            }
        }
        if (count($fks) === 2 && $fks[$pk[0]][0] !== $fks[$pk[1]][0]) {
            return $fks;
        } else {
            return false;
        }
    }

    /**
     * Generate a relation name for the specified table and a base name.
     * @param array $relations the relations being generated currently.
     * @param string $className the class name that will contain the relation declarations
     * @param \yii\db\TableSchema $table the table schema
     * @param string $key a base name that the relation name may be generated from
     * @param boolean $multiple whether this is a has-many relation
     * @return string the relation name
     */
    protected function generateRelationName($relations, $className, $table, $key, $multiple)
    {
        if(!empty($key)){
            if (substr_compare($key, 'id', -2, 2, true) === 0 && strcasecmp($key, 'id')) {
                $key = rtrim(substr($key, 0, -2), '_');
            }elseif (substr_compare($key, 'id', 0, 2, true) === 0 && strcasecmp($key, 'id')) {
                $key = ltrim(substr($key, 2), '_');
            }
        }

        if ($multiple) {
            $key = Inflector::pluralize($key);
        }
        $name = $rawName = Inflector::id2camel($key, '_');
        $i = 0;
        while (isset($table->columns[lcfirst($name)])) {
            $name = $rawName . ($i++);
        }
        while (isset($relations[$className][lcfirst($name)])) {
            $name = $rawName . ($i++);
        }

        return $name;
    }

    /**
     * Validates the [[db]] attribute.
     */
    public function validateDb()
    {
        if (!Yii::$app->has($this->db)) {
            $this->addError('db', 'There is no application component named "db".');
        } elseif (!Yii::$app->get($this->db) instanceof Connection) {
            $this->addError('db', 'The "db" application component must be a DB connection instance.');
        }
    }

    /**
     * Validates the [[ns]] attribute.
     */
    public function validateNamespace()
    {
        $this->ns = ltrim($this->ns, '\\');
        $path = Yii::getAlias('@' . str_replace('\\', '/', $this->ns), false);
        if ($path === false) {
            $this->addError('ns', 'Namespace must be associated with an existing directory.');
        }
    }

    /**
     * Validates the [[modelClass]] attribute.
     */
    public function validateModelClass()
    {
        if ($this->isReservedKeyword($this->modelClass)) {
            $this->addError('modelClass', 'Class name cannot be a reserved PHP keyword.');
        }
        if ((empty($this->tableName) || substr_compare($this->tableName, '*', -1, 1)) && $this->modelClass == '') {
            $this->addError('modelClass', 'Model Class cannot be blank if table name does not end with asterisk.');
        }
    }

    /**
     * Validates the [[tableName]] attribute.
     */
    public function validateTableName()
    {
        if (strpos($this->tableName, '*') !== false && substr_compare($this->tableName, '*', -1, 1)) {
            $this->addError('tableName', 'Asterisk is only allowed as the last character.');

            return;
        }
        $tables = $this->getTableNames();
        if (empty($tables)) {
            $this->addError('tableName', "Table '{$this->tableName}' does not exist.");
        } else {
            foreach ($tables as $table) {
                $class = $this->generateClassName($table);
                if ($this->isReservedKeyword($class)) {
                    $this->addError('tableName', "Table '$table' will generate a class which is a reserved PHP keyword.");
                    break;
                }
            }
        }
    }

    protected $tableNames;
    protected $classNames;

    /**
     * @return array the table names that match the pattern specified by [[tableName]].
     */
    protected function getTableNames()
    {
        if ($this->tableNames !== null) {
            return $this->tableNames;
        }
        $db = $this->getDbConnection();
        if ($db === null) {
            return [];
        }
        $tableNames = [];
        if (strpos($this->tableName, '*') !== false) {
            if (($pos = strrpos($this->tableName, '.')) !== false) {
                $schema = substr($this->tableName, 0, $pos);
                $pattern = '/^' . str_replace('*', '\w+', substr($this->tableName, $pos + 1)) . '$/';
            } else {
                $schema = '';
                $pattern = '/^' . str_replace('*', '\w+', $this->tableName) . '$/';
            }

            foreach ($db->schema->getTableNames($schema) as $table) {
                if (preg_match($pattern, $table)) {
                    $tableNames[] = $schema === '' ? $table : ($schema . '.' . $table);
                }
            }
        } elseif (($table = $db->getTableSchema($this->tableName, true)) !== null) {
            $tableNames[] = $this->tableName;
            $this->classNames[$this->tableName] = $this->modelClass;
        }

        return $this->tableNames = $tableNames;
    }

    /**
     * Generates the table name by considering table prefix.
     * If [[useTablePrefix]] is false, the table name will be returned without change.
     * @param string $tableName the table name (which may contain schema prefix)
     * @return string the generated table name
     */
    public function generateTableName($tableName)
    {
        if (!$this->useTablePrefix) {
            return $tableName;
        }

        $db = $this->getDbConnection();
        if (preg_match("/^{$db->tablePrefix}(.*?)$/", $tableName, $matches)) {
            $tableName = '{{%' . $matches[1] . '}}';
        } elseif (preg_match("/^(.*?){$db->tablePrefix}$/", $tableName, $matches)) {
            $tableName = '{{' . $matches[1] . '%}}';
        }
        return $tableName;
    }

    /**
     * Generates a class name from the specified table name.
     * @param string $tableName the table name (which may contain schema prefix)
     * @return string the generated class name
     */
    protected function generateClassName($tableName)
    {
        if (isset($this->classNames[$tableName])) {
            return $this->classNames[$tableName];
        }

        if (($pos = strrpos($tableName, '.')) !== false) {
            $tableName = substr($tableName, $pos + 1);
        }

        $db = $this->getDbConnection();
        $patterns = [];
        $patterns[] = "/^{$db->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$db->tablePrefix}$/";
        if (strpos($this->tableName, '*') !== false) {
            $pattern = $this->tableName;
            if (($pos = strrpos($pattern, '.')) !== false) {
                $pattern = substr($pattern, $pos + 1);
            }
            $patterns[] = '/^' . str_replace('*', '(\w+)', $pattern) . '$/';
        }
        $className = $tableName;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $tableName, $matches)) {
                $className = $matches[1];
                break;
            }
        }

        return $this->classNames[$tableName] = Inflector::id2camel($className, '_');
    }

    /**
     * @return Connection the DB connection as specified by [[db]].
     */
    protected function getDbConnection()
    {
        return Yii::$app->get($this->db, false);
    }

    /**
     * Checks if any of the specified columns is auto incremental.
     * @param \yii\db\TableSchema $table the table schema
     * @param array $columns columns to check for autoIncrement property
     * @return boolean whether any of the specified columns is auto incremental.
     */
    protected function isColumnAutoIncremental($table, $columns)
    {
        foreach ($columns as $column) {
            if (isset($table->columns[$column]) && $table->columns[$column]->autoIncrement) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $columns
     * @return bool
     */
    public function timestampBehavior($columns)
    {
        $createName  = '';
        $updateName  = '';
        foreach ($columns as $column) {

            if ($this->getFieldComment($column) == 'created_at') {
                $createName = $column->name;
                continue;
            }
            if ($this->getFieldComment($column) == 'updated_at') {
                $updateName = $column->name;
                continue;
            }
        }

        $attributes = '';
        if ($createName && $updateName) {
            $attributes .= "\n\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_INSERT => ['" . $createName . "', '" . $updateName . "'],";
            $attributes .= "\n\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_UPDATE => ['" . $updateName . "']";
        } else if($createName){
            $attributes .= "\n\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_INSERT => ['" . $createName . "']";
        } else if($updateName){
            $attributes .= "\n\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_UPDATE => ['" . $updateName . "']";
        }

        if ($attributes) {
            return "\t\t\t[\n\t\t\t\t'class' => \\yii\\behaviors\\TimestampBehavior::class,\n\t\t\t\t'attributes' => [" . $attributes . "\n\t\t\t\t], \n\t\t\t\t'value' => new \\yii\\db\\Expression('NOW()')\n\t\t\t],\n";
        }
        return false;
    }

    /**
     * @param $columns
     * @return bool
     */
    public function haveWeight($columns)
    {
        foreach ($columns as $column) {
            if ($this->getFieldComment($column) == 'weight') {
                return $column->name;
            }
        }
        return false;
    }


    /**
     * @return \yii\db\TableSchema
     */
    public function getTableSchema()
    {
        $db = $this->getDbConnection();
        return $db->getTableSchema($this->tableName, true);
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
                    if($this->getFieldComment($column) == 'parent' || $this->getFieldComment($column) == 'id_parent'){
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
     * @return int|null|string
     */
    public function getNameAttribute()
    {
        $tableSchema = $this->getTableSchema();

        if($tableSchema){
            foreach ($tableSchema->columns as $column) {
                if(in_array($this->getFieldComment($column), ['name', 'title'])){
                    return $column->name;
                }
            }
        }

        if($this->modelLangClass){
            $classLang = $this->modelLangClass;
            $schema = $classLang::getTableSchema();
            foreach ($schema->columns as $name => $column) {
                if(in_array($this->getFieldComment($column), ['name', 'title'])){
                    return $column->name;
                }
            }
        }
        return 'id';
    }

    /**
     * @param @param $column yii\db\TableSchema
     * @param bool $forLabel
     * @return mixed
     */
    public function getFieldComment($column, $forLabel = false)
    {
        $parts = explode(' ', $column->comment);
        if ($forLabel && sizeof($parts) > 1) {
            unset($parts['0']);
            return join(' ', $parts);
        }

        return strtolower($parts['0']);
    }
}
