<?php
namespace Fuel\Migrations;

class Usuarios
{

    function up()
    {
        \DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'id_dispositivo' => array('type' => 'int', 'constraint' => 5, 'null' => true),
            'id_rol' => array('type' => 'int', 'constraint' => 5, 'null' => false),
            'id_privacidad' => array('type' => 'int', 'constraint' => 5, 'null' => false),
            'nombre' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'password' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'email' => array('type' => 'int', 'constraint' => 5, 'null' => false),
            'fotoPerfil' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'x' => array('type' => 'decimal', 'constraint' => 50, 'null' => true),
            'y' => array('type' => 'decimal', 'constraint' => 50, 'null' => true),
            'cumpleaños' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'ciudad' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'descripcion' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaUsersARols',
                    'key' => 'id_rol',
                    'reference' => array(
                        'table' => 'roles',
                        'column' => 'id',
                    ),
                     'on_update' => 'CASCADE',
                     'on_delete' => 'CASCADE',
                ),
                array(
                    'constraint' => 'claveAjenaUsersAPrivate',
                    'key' => 'id_privacidad',
                    'reference' => array(
                        'table' => 'privacidad',
                        'column' => 'id',
                    ),
                     'on_update' => 'CASCADE',
                     'on_delete' => 'CASCADE',
                )
            )
    );
    \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`nombre`)")->execute();
    \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`email`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('usuarios');
    }
}