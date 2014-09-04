<?php
namespace dokku_alt;
class Model_Access_Deploy extends Model_Access
{
    function init()
    {
        parent::init();

        $this->addCondition('type','deploy');
        $this->hasOne('dokku_alt/App',null,null,'App');

        $this->addHook('beforeSave',$this);
    }
    function beforeSave(){
        //$this->cmd();

    }
    function cmd($command, $args=[]){
        $app=$this->ref('app_id');
        array_unshift($args, $this['name']);
        return $app->ref('host_id')->executeCommand('deploy:'.$command, $args);
    }
}
