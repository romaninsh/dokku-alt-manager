<?php
/**
 * User implementation
 */
class Model_User extends SQL_Model
{
    public $table="user";
    public $title_field='email';
    function init()
    {
        parent::init();

        $this->addField('email');
        $this->addField('password');
    }
}
