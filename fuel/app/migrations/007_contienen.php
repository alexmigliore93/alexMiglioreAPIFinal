<?php
namespace Fuel\Migrations; 
class Contienen
{
    function up()
    {
        \DBUtil::create_table('contienen', array(
            'id_list' => array('type' => 'int', 'constraint' => 5),
            'id_song' => array('type' => 'int', 'constraint' => 5),
        ), array('id_list','id_song'), false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenacontienenAlistas',
                    'key' => 'id_list',
                    'reference' => array(
                        'table' => 'lists',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenacontienenACanciones',
                    'key' => 'id_song',
                    'reference' => array(
                        'table' => 'songs',
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
       \DBUtil::drop_table('contienen');
    }
}