<?php 
namespace Fuel\Migrations;
class Privacity
{
    function up()
    {
        \DBUtil::create_table('privacity', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true),
            'profile' => array('type' => 'boolean'),
            'friends' => array('type' => 'boolean'),
            'lists' => array('type' => 'boolean'),
            'notifications' => array('type' => 'boolean'),
            'localization' => array('type' => 'boolean'),
        ), array('id'));
    }
    function down()
    {
       \DBUtil::drop_table('privacity');
    }
}