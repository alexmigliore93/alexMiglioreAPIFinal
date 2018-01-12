<?php
namespace Fuel\Migrations;

class Noticias
{

    function up()
    {
        \DBUtil::create_table('noticias', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'id_usuario' => array('type' => 'int', 'constraint' => 11),
            'titulo' => array('type' => 'varchar', 'constraint' => 50),
            'descripcion' => array('type' => 'varchar', 'constraint' => 50),
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaNoticiasAUsuarios',
                    'key' => 'id_usuario',
                    'reference' => array(
                        'table' => 'usuarios',
                        'column' => 'id',
                    ),
                     'on_update' => 'CASCADE',
                     'on_delete' => 'CASCADE',
                ),
    ));
    }

    function down()
    {
       \DBUtil::drop_table('noticias');
    }
}