<?php
/**
 * Model implementation
 */
namespace dokku_alt;
class Model_DB_Link extends \SQL_Model
{
    public $table="db_link";

    function init()
    {
        parent::init();

        $this->hasOne('dokku_alt/DB',null,null,'DB');
        $this->hasOne('dokku_alt/App',null,null,'App');
    }
}
