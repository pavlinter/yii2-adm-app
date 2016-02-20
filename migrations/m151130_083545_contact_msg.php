<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_083545_contact_msg extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%contact_msg}}', [
            'id' => Schema::TYPE_PK,
            'from_email' => Schema::TYPE_STRING . "(320) NOT NULL",
            'to_email' => Schema::TYPE_STRING . "(320) NULL",
            'subject' => Schema::TYPE_STRING . "(300) NOT NULL",
            'text' => Schema::TYPE_TEXT,
            'viewed' => Schema::TYPE_BOOLEAN . "(1) NOT NULL DEFAULT '0'",
            'created_at' => Schema::TYPE_TIMESTAMP . " NULL",
            'updated_at' => Schema::TYPE_TIMESTAMP . " NULL",
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%contact_msg}}');
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
