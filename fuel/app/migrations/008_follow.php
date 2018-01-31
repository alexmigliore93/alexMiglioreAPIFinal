<?php
namespace Fuel\Migrations; 
class Follow
{
    function up()
    {
        \DBUtil::create_table('follow', array(
            'id_user' => array('type' => 'int', 'constraint' => 5),
            'id_user2' => array('type' => 'int', 'constraint' => 5),
        ), array('id_user','id_user2'), false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenafollowAusers',
                    'key' => 'id_user',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenafollowAusers2',
                    'key' => 'id_user2',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
            )
        );
    }
    function down()
    {
       \DBUtil::drop_table('follow');
    }
}