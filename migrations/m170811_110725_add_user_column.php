<?php

use yii\db\Migration;

class m170811_110725_add_user_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'firstname', 'VARCHAR(50) AFTER `username` ');
        $this->addColumn('{{%user}}', 'lastname', 'VARCHAR(50) AFTER `firstname` ');
        $this->addColumn('{{%user}}', 'gender', 'TINYINT(1) AFTER `lastname`');
        $this->addColumn('{{%user}}', 'display_type', 'TINYINT(1) AFTER `status`');
        $this->addColumn('{{%user}}', 'social_type', 'TINYINT(1) AFTER `display_type`');
        $this->addColumn('{{%user}}', 'social_id', 'VARCHAR(300) AFTER `social_type`');
        $this->addColumn('{{%user}}', 'cash', 'DOUBLE(7,2) AFTER `social_id`');
        $this->addColumn('{{%user}}', 'online', 'TIMESTAMP AFTER `cash`');
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'firstname');
        $this->dropColumn('{{%user}}', 'lastname');
        $this->dropColumn('{{%user}}', 'gender');
        $this->dropColumn('{{%user}}', 'display_type');
        $this->dropColumn('{{%user}}', 'social_type');
        $this->dropColumn('{{%user}}', 'social_id');
        $this->dropColumn('{{%user}}', 'cash');
        $this->dropColumn('{{%user}}', 'online');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170811_110725_add_user_column cannot be reverted.\n";

        return false;
    }
    */
}
