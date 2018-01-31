<?php 
class Model_Users extends Orm\Model
{
    protected static $_table_name = 'users';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', 
        'username' => array(
            'data_type' => 'varchar'   
        ),
        'password' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'x' => array(
            'data_type' => 'float'   
        ),
        'y' => array(
            'data_type' => 'float'   
        ),
        'id_dispositivo' => array(
            'data_type' => 'varchar'   
        ),
        'photo' => array(
            'data_type' => 'varchar'   
        ),
        'id_rol' => array(
            'data_type' => 'int'   
        ),
        'birthdate' => array(
            'data_type' => 'varchar'   
        ),
        'city' => array(
            'data_type' => 'varchar'   
        ),
        'description' => array(
            'data_type' => 'varchar'   
        ),
        'id_privacity' => array(
            'data_type' => 'int'   
        )
    );
}