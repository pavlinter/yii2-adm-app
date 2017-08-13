<?php

use yii\db\Migration;

class m170811_110044_notification extends Migration
{
    public function safeUp()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        /* MYSQL */
        if (!in_array('notification', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%notification}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'from_id' => 'INT(11) NULL COMMENT \'select2\'',
                    'to_id' => 'INT(11) NOT NULL COMMENT \'select2\'',
                    'type' => 'TINYINT(1) NOT NULL COMMENT \'название какой то таблицы\'',
                    'row_id' => 'INT(11) UNSIGNED NULL COMMENT \'id из какой то таблицы\'',
                    'message' => 'TINYINT(3) UNSIGNED NOT NULL COMMENT \'Yii:t key\'',
                    'data' => 'TEXT NULL COMMENT \'серелизованые данные\'',
                    'viewed' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'checkbox\'',
                    'removed' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'checkbox\'',
                    'created_at' => 'TIMESTAMP NULL COMMENT \'created_at\'',
                    'updated_at' => 'TIMESTAMP NULL COMMENT \'updated_at\'',
                ], $tableOptions_mysql);
            }
        }

        $this->createIndex('index_from_id','{{%notification}}','from_id',0);
        $this->createIndex('index_to_id','{{%notification}}','to_id',0);
        $this->createIndex('index_from_id_to_id_type_row_id_viewed_4348_02','{{%notification}}','from_id,to_id,type,row_id,viewed',0);

        $this->execute('SET foreign_key_checks = 0;');
        $this->addForeignKey('notification_ibfk_1','{{%notification}}', 'from_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey('notification_ibfk_2','{{%notification}}', 'to_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE' );
        $this->execute('SET foreign_key_checks = 1;');
        return true;
    }

    public function safeDown()
    {
        $this->execute('DROP TABLE IF EXISTS `notification`');
        return true;
    }


}
