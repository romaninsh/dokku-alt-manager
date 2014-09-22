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

        $this->addHook('beforeSave,beforeDelete', $this);
    }

    function beforeSave(){
        // create link
        if($this->id)return;

        $db=$this->ref('db_id');
        $db->cmd('link', [$this->ref('app_id')->get('name')]);
    }
    function beforeDelete(){
        $db=$this->ref('db_id');
        $db->cmd('unlink', [$this->ref('app_id')->get('name')]);

    }
}
