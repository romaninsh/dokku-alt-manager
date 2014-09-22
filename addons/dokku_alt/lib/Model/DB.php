<?php
namespace dokku_alt;
class Model_DB extends \SQL_Model {
    public $table='db';

    function init(){
        parent::init();


        $this->addField('name');
        $this->addField('type')->enum(['mongodb','mariadb','postrgresql','redis','memcache'])->mandatory(true);

        $this->addHook('beforeInsert,beforeDelete',$this);

        $this->hasOne('dokku_alt/Host');
        $this->hasMany('dokku_alt/DB_Link',null,null,'DB_Link');
    }
    function cmd($command, $args=[]){
        array_push($args, $this['name']);
        return $this->ref('host_id')->executeCommand($this['type'].':'.$command, $args);
    }
    function beforeInsert(){
        $this->cmd('create');
    }
    function beforeDelete(){
        $this->ref('DB_Link')->each('delete');
        $this->cmd('delete');
    }
}
