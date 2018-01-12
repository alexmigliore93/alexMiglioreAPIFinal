<?php
namespace Fuel\Migrations;

class Canciones
{

    function up()
    {
        \DBUtil::create_table('canciones', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'titulo' => array('type' => 'varchar', 'constraint' => 50),
            'artista' => array('type' => 'varchar', 'constraint' => 50),
            'url' => array('type' => 'varchar', 'constraint' => 50), 
            'reproducciones' => array('type' => 'int', 'constraint' => 11),
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('canciones');
    }
}