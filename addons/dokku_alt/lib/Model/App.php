<?php
namespace dokku_alt;
class Model_App extends  \SQL_Model {
    public $table='app';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/Host');
        $this->addField('name');
        $this->addField('url');
        $this->addField('is_started')->type('boolean');
        $this->addField('is_enabled')->type('boolean')->defaultValue(true);

        $this->addHook('beforeSave',$this);
        //$this->addHook('afterSave',$this);
//        $this->addHook('afterInsert',$this);
    }

    function beforeSave(){
        if($this->isDirty('is_started')){
            if($this['is_started']){
                $this->start();
            }else{
                $this->stop();
            }
        }
        if($this->isDirty('is_enabled')){
            if($this['is_enabled']){
                $this->enable();
            }else{
                $this->disable();
            }
        }


        if($this['is_started']===null){
            $this['is_started'] = explode(' ',$this->cmd('status'))[2] == 'running.';
        }
        if(!$this['url']){
            $this['url'] = $this->getURL();
        }
    }
    function discover(){
        $this['is_started']=null;
        $this['url']=null;
        $this->save();
    }

    function cmd($command, $args=[]){
        array_unshift($args, $this['name']);
        return $this->ref('host_id')->executeCommand('apps:'.$command, $args);
    }

    function top(){
        return $this->cmd('top');
    }
    function disable(){
        $ret = $this->cmd('disable');
        return $ret;
    }
    function enable(){
        $ret = $this->cmd('enable');
        return $ret;
    }
    function start(){
        $ret = $this->cmd('start');
        return $ret;
    }
    function stop(){
        $ret = $this->cmd('stop');
        return $ret;
    }
    function getURL(){
        return $this->ref('host_id')->executeCommand('url', [$this['name']]);
    }
}
