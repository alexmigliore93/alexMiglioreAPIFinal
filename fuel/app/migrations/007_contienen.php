<?php
namespace Fuel\Migrations;

class Contienen
{

    function up()
    {
        \DBUtil::create_table('contienen', array(
            'Id_lista' => array('type' => 'int', 'constraint' => 5),
            'Id_canciones' => array('type' => 'int', 'constraint' => 5)
        ), array('Id_lista', 'Id_canciones'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaSeguirAIdlista',
                    'key' => 'id_lista',
                    'reference' => array(
                        'table' => 'listas',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenaSeguirAIdcanciones',
                    'key' => 'id_canciones',
                    'reference' => array(
                        'table' => 'canciones',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('contienen');
    }
}