<?php 
class Model_Rols extends Orm\Model
{
    protected static $_table_name = 'rols';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', 
        'type' => array(
            'data_type' => 'varchar'   
        )
    );
}