<?php 
namespace Fuel\Migrations;
class Users
{
    function up()
    {
        \DBUtil::create_table('users', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true),
            'username' => array('type' => 'varchar', 'constraint' => 50),
            'email' => array('type' => 'varchar', 'constraint' => 50),
            'password' => array('type' => 'varchar', 'constraint' => 50),
            'x' => array('type' => 'float', 'constraint' => 50 , 'null' => true),
            'y' => array('type' => 'float', 'constraint' => 50, 'null' => true),
            'id_dispositivo' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
            'photo' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'id_rol' => array('type' => 'int', 'constraint' => 11),
            'birthdate' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
            'city' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
            'description' => array('type' => 'varchar', 'constraint' => 240, 'null' => true),
            'id_privacity' => array('type' => 'int', 'constraint' => 11)
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaUsersARols',
                    'key' => 'id_rol',
                    'reference' => array(
                        'table' => 'rols',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenaUsersAPrivacity',
                    'key' => 'id_privacity',
                    'reference' => array(
                        'table' => 'privacity',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            )
        );
        \DB::query("ALTER TABLE `users` ADD UNIQUE (`username`)")->execute();
        \DB::query("ALTER TABLE `users` ADD UNIQUE (`email`)")->execute();
    }
    function down()
    {
       \DBUtil::drop_table('users');
    }
}