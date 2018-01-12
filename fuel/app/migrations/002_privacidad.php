<?php
namespace Fuel\Migrations;

class Privacidad
{

    function up()
    {
        \DBUtil::create_table('privacidad', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'perfil' => array('type' => 'bool'),
            'amigos' => array('type' => 'bool'),
            'listas' => array('type' => 'bool'),
            'notificaciones' => array('type' => 'bool'), 
            'localizacion' => array('type' => 'bool'), 
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('privacidad');
    }
}