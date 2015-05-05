<?php
namespace dokku_alt;
class Model_Config extends Model_Abstract {
    public $table='config';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/App');

        $this->addField('name');
        $this->addField('value');

        $this->addHook('beforeInsert,beforeDelete,beforeModify',$this);
    }
    function cmd($command, $args=[]){

        $app = $this->ref('app_id');
        $host = $app->ref('host_id');

        return $host->executeCommand('config:'.$command, [$app['name'], join('=', $args)]);
    }
    function beforeInsert(){
        $this->cmd('set', [$this['name'],$this['value']]);
    }
    function beforeDelete(){
        $this->cmd('unset', [$this['name']]);
    }
    function beforeModify(){
        //$this->cmd('delete', [$this['name']]);
        $this->cmd('set', [$this['name'],$this['value']]);
    }
}
