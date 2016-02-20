<?php

use yii\db\Schema;
use yii\db\Migration;

class m150309_104532_pages_data extends Migration
{
    public $rolesTranslations = [
        'AdmRoot' => 'Root',
        'AdmAdmin' => 'Admin',
        'Adm-User' => 'Users',
        'Adm-Language' => 'Languages',
        'Adm-FilesRoot' => 'Media Files (Root)',
        'Adm-FilesAdmin' => 'Media Files (Admin)',
        'Adm-TranslRoot' => 'Translations',
        'Adm-Pages' => 'Pages',
    ];

    public function up()
    {
        $this->batchInsert('{{%page}}', ['id', 'id_parent', 'layout', 'type', 'weight', 'created_at', 'updated_at'],[
            [
                1,
                null,
                'page',
                'page',
                50,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
            [
                2,
                null,
                'page',
                'page',
                100,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
            [
                3,
                null,
                'page',
                'page',
                150,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
            [
                4,
                1,
                'main',
                'main',
                200,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
            [
                5,
                1,
                'page',
                'page',
                250,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
            [
                6,
                1,
                'contact',
                'page',
                300,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ],
        ]);


        $this->batchInsert('{{%page_lang}}', ['page_id', 'language_id', 'name', 'title', 'alias', 'text'],[
            [1, 1, 'Menu 1', 'Menu 1', null, null],
            [1, 2, 'Menu 1', 'Menu 1', null, null],

            [2, 1, 'Menu 2', 'Menu 2', null, null],
            [2, 2, 'Menu 2', 'Menu 2', null, null],

            [3, 1, 'Menu 3', 'Menu 3', null, null],
            [3, 2, 'Menu 3', 'Menu 3', null, null],

            [4, 1, 'Main Page', 'Main Page', 'main', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non felis ligula. Maecenas at nunc velit. Vestibulum ornare augue odio, non iaculis massa laoreet ac. Curabitur gravida blandit interdum. Suspendisse ligula erat, blandit non molestie ac, ullamcorper nec ligula. Phasellus a lacus quis augue placerat lacinia. Sed pretium rutrum auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris nec dui non turpis porta convallis ut bibendum urna. Cras sagittis dapibus lacus, et interdum magna feugiat vitae.'],
            [4, 2, 'Main Page', 'Main Page', 'main', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non felis ligula. Maecenas at nunc velit. Vestibulum ornare augue odio, non iaculis massa laoreet ac. Curabitur gravida blandit interdum. Suspendisse ligula erat, blandit non molestie ac, ullamcorper nec ligula. Phasellus a lacus quis augue placerat lacinia. Sed pretium rutrum auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris nec dui non turpis porta convallis ut bibendum urna. Cras sagittis dapibus lacus, et interdum magna feugiat vitae.'],

            [5, 1, 'About', 'About', 'about', 'Etiam sit amet sagittis odio, et ultrices metus. Integer lacinia, libero eu ultricies lacinia, nunc lacus accumsan turpis, vitae feugiat magna quam ac nulla. Sed posuere ipsum sed nisl rhoncus suscipit. Suspendisse varius elit mauris, eget accumsan mauris accumsan vel. Sed lobortis, nunc ac fermentum cursus, leo quam tristique lectus, non mollis lorem ex in libero. Donec hendrerit congue convallis. Praesent sed nibh interdum, finibus nisl nec, sodales urna. Suspendisse laoreet ac neque at condimentum. Nunc ac tortor et est mollis condimentum. Nam auctor purus at mi scelerisque scelerisque. Fusce id convallis mauris. Fusce malesuada mattis laoreet. Quisque fermentum, risus eu blandit laoreet, felis lacus tincidunt erat, a dignissim felis leo at urna. Ut sit amet ligula tortor. Proin mattis nunc lectus.'],
            [5, 2, 'About', 'About', 'about', 'Etiam sit amet sagittis odio, et ultrices metus. Integer lacinia, libero eu ultricies lacinia, nunc lacus accumsan turpis, vitae feugiat magna quam ac nulla. Sed posuere ipsum sed nisl rhoncus suscipit. Suspendisse varius elit mauris, eget accumsan mauris accumsan vel. Sed lobortis, nunc ac fermentum cursus, leo quam tristique lectus, non mollis lorem ex in libero. Donec hendrerit congue convallis. Praesent sed nibh interdum, finibus nisl nec, sodales urna. Suspendisse laoreet ac neque at condimentum. Nunc ac tortor et est mollis condimentum. Nam auctor purus at mi scelerisque scelerisque. Fusce id convallis mauris. Fusce malesuada mattis laoreet. Quisque fermentum, risus eu blandit laoreet, felis lacus tincidunt erat, a dignissim felis leo at urna. Ut sit amet ligula tortor. Proin mattis nunc lectus.'],

            [6, 1, 'Contact', 'Contact', 'contact', 'Curabitur congue augue ligula, vel ullamcorper ligula laoreet sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque eget sollicitudin elit, sit amet ultrices erat. Nulla ut pulvinar mauris. Vestibulum imperdiet gravida finibus. Nunc venenatis, orci a euismod molestie, massa quam consequat tellus, venenatis congue mauris tortor vel elit. Donec ac lacinia elit.'],
            [6, 2, 'Contact', 'Contact', 'contact', 'Curabitur congue augue ligula, vel ullamcorper ligula laoreet sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque eget sollicitudin elit, sit amet ultrices erat. Nulla ut pulvinar mauris. Vestibulum imperdiet gravida finibus. Nunc venenatis, orci a euismod molestie, massa quam consequat tellus, venenatis congue mauris tortor vel elit. Donec ac lacinia elit.'],
        ]);

        foreach ($this->rolesTranslations as $role => $transl) {
            $this->insert('{{%source_message}}', [
                'category' => 'adm/sumoselect/items',
                'message' => $role,
            ]);
            $this->insert('{{%message}}', [
                'id' => $this->db->lastInsertID,
                'language_id' => 1,
                'translation' => $transl,
            ]);
        }

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'],[
            [
                'AdmAdmin',
                'Adm-TranslRoot',
            ],
        ]);
    }

    public function down()
    {
        $this->delete('{{%page}}', "id IN (1,2,3,4,5,6)");
        $this->delete('{{%source_message}}', "category='adm/sumoselect/items' AND message IN ('" . implode("','", array_keys($this->rolesTranslations)) . "')");
        $this->delete('{{%auth_item_child}}', "parent='AdmAdmin' AND child='Adm-TranslRoot'");
    }
}
