<?php

use yii\db\Migration;

class m170812_054514_config extends Migration
{
    public $auth_item = [];

    public $auth_item_child = [];

    public function init()
    {
        parent::init();
        $this->auth_item = [
            [
                'manager',
                1,
                'The manager role for adm',
                NULL,
                NULL,
                time(),
                time(),
            ],
            [
                'user',
                1,
                'The role for simple user',
                NULL,
                NULL,
                time(),
                time(),
            ],
            [
                'manager-tasks',
                2,
                'The role all tasks for manager',
                NULL,
                NULL,
                time(),
                time(),
            ],
            [
                'user-tasks',
                2,
                'The role all tasks for user',
                NULL,
                NULL,
                time(),
                time(),
            ],
        ];
        $this->auth_item_child = [
            [
                'AdmRoot',
                'manager-tasks',
            ],
            [
                'AdmRoot',
                'user-tasks',
            ],
            [
                'AdmAdmin',
                'manager-tasks',
            ],
            [
                'AdmAdmin',
                'user-tasks',
            ],
            [
                'manager',
                'manager-tasks',
            ],
            [
                'manager',
                'user-tasks',
            ],
            [
                'user',
                'user-tasks',
            ],
        ];
    }

    public function safeUp()
    {
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], $this->auth_item);
        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], $this->auth_item_child);

        return true;
    }

    public function safeDown()
    {
        foreach ($this->auth_item as $data) {
            $this->delete('{{%auth_item}}', "name='" . $data['0'] . "'");
        }
        foreach ($this->auth_item_child as $data) {
            $this->delete('{{%auth_item_child}}', "parent='" . $data[0] . "' AND child='" . $data[1] . "'");
        }
        return true;
    }
}
