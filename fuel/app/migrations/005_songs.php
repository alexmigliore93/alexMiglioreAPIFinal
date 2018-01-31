<?php 
namespace Fuel\Migrations;
class Songs
{
    function up()
    {
        \DBUtil::create_table('songs', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 50, 'unique'=> true),
            'artist' => array('type' => 'varchar', 'constraint' => 50),
            'url' => array('type' => 'varchar', 'constraint' => 50),
            'plays' => array('type' => 'int', 'constraint' => 10),
        ), array('id'));
        \DB::query("ALTER TABLE `songs` ADD UNIQUE (`url`)")->execute();
    }
    function down()
    {
       \DBUtil::drop_table('songs');
    }
}