<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m151130_083545_contact_msg
 */
class m151130_083545_contact_msg extends Migration
{
    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
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

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->dropTable('{{%contact_msg}}');
        return false;
    }
}
