<?php

namespace app\modules\admgii\generators\table;

use Yii;
use yii\db\Connection;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \app\modules\admgii\Generator
{
    public $db = 'db';
    public $tableName;
    public $isLang = false;
    public $useTablePrefix = true;

    private $tablePrefix;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Table Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generate table to database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['db', 'tableName'], 'filter', 'filter' => 'trim'],
            [['db', 'tableName'], 'required'],
            [['db'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['tableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
            [['db'], 'validateDb'],
            [['tableName'], 'validateTableName'],
            [['useTablePrefix', 'isLang'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'db' => 'Database Connection ID',
            'tableName' => 'Table Name',
            'isLang' => 'Create Language Table',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'db' => 'This is the ID of the DB application component.',
            'tableName' => 'This is the name of the DB table that the new ActiveRecord class is associated with, e.g. <code>post</code>.
                The table name may consist of the DB schema part if needed, e.g. <code>public.post</code>.
                The table name may end with asterisk to match multiple table names, e.g. <code>tbl_*</code>
                will match tables who name starts with <code>tbl_</code>. In this case, multiple ActiveRecord classes
                will be generated, one for each matching table name; and the class names will be generated from
                the matching characters. For example, table <code>tbl_post</code> will generate <code>Post</code>
                class.'

        ]);
    }




    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['db']);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if ($this->useTablePrefix) {
            $this->tablePrefix = $this->getDbConnection()->tablePrefix;
        }
        $sql = Yii::$app->view->renderPhpFile(Yii::getAlias('@admgii/generators/table/sql.php'), [
            'generator' => $this,
            'tableName' => $this->tableName,
            'table' => $this->tablePrefix . $this->tableName,
            'tablePrefix' => $this->tablePrefix,
            'tableLang' => $this->tablePrefix . $this->tableName . '_lang',
        ]);

        $this->execute($sql);
    }

    /**
     * Executes a SQL statement.
     * This method executes the specified SQL statement using [[db]].
     * @param string $sql the SQL statement to be executed
     * @param array $params input parameters (name => value) for the SQL execution.
     * See [[Command::execute()]] for more details.
     */
    public function execute($sql, $params = [])
    {
        $this->getDbConnection()->createCommand($sql)->bindValues($params)->execute();
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
     * Validates the [[tableName]] attribute.
     */
    public function validateTableName()
    {
        if (strpos($this->tableName, '*') !== false && substr_compare($this->tableName, '*', -1, 1)) {
            $this->addError('tableName', 'Asterisk is only allowed as the last character.');
            return;
        }

        $db = $this->getDbConnection();
        $exist = $db->getTableSchema($this->tableName, true);
        if ($exist) {
            $this->addError('tableName', "Table '{$this->tableName}' already exist.");
        }
    }


    /**
     * @return Connection the DB connection as specified by [[db]].
     */
    protected function getDbConnection()
    {
        return Yii::$app->get($this->db, false);
    }

    /**
     * Returns the message to be displayed when the newly generated code is saved successfully.
     * Child classes may override this method to customize the message.
     * @return string the message to be displayed when the newly generated code is saved successfully.
     */
    public function successMessage()
    {
        return 'The table has been generated successfully.';
    }
}
