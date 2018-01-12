<?php
namespace Fuel\Migrations;

class Siguen
{

    function up()
    {
        \DBUtil::create_table('siguen', array(
            'Id_seguido' => array('type' => 'int', 'constraint' => 5),
            'Id_seguidor' => array('type' => 'int', 'constraint' => 5)
        ), array('Id_seguido', 'Id_seguidor'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaSeguirAIdseguido',
                    'key' => 'id_seguido',
                    'reference' => array(
                        'table' => 'usuarios',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenaSeguirAIdseguidor',
                    'key' => 'id_seguidor',
                    'reference' => array(
                        'table' => 'usuarios',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('siguen');
    }
}