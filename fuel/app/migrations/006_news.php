<?php 
namespace Fuel\Migrations;
class News
{
    function up()
    {
        \DBUtil::create_table('news', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 50, 'unique'=> true),
            'description' => array('type' => 'varchar', 'constraint' => 50),
            'id_user' => array('type' => 'int', 'constraint' => 9),
        ), array('id') ,false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaNewsAUsers',
                    'key' => 'id_user',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            )
        );
    }
    function down()
    {
       \DBUtil::drop_table('news');
    }
}