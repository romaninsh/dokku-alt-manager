<?php
namespace dokku_alt;
class Model_Config extends \SQL_Model {
    public $table='config';

    public $sync = true;

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/App');

        $this->addField('name');
        $this->addField('value');

        $this->addHook('beforeInsert,beforeDelete,beforeModify',$this);
    }

    function sync(\View_Console $c=null){
        $app = $this->ref('app_id');
        $host = $app->ref('host_id');

        $this->sync = false;

        $config = $host->executeCommand('config', [$app['name']]);
        // break by lines
        $cnt = 0;
        foreach(explode("\n", $config) as $line){
            list($key,$val)=explode(":",$line,2);
            if(!$key || !$val)continue;
            $key=trim($key);$val=trim($val);

            $this->tryLoadBy('name', $key);
            $this['name']=$key;
            $this['value']=$val;
            $this->saveAndUnload();
            $cnt++;
        }
        $c->out('Stored '.$cnt.' config variables');
    }



    function cmd($command, $args=[]){

        $app = $this->ref('app_id');
        $host = $app->ref('host_id');

        return $host->executeCommand('config:'.$command, [$app['name'], join('=', $args)]);
    }
    function beforeInsert(){
        if($this->sync)$this->cmd('set', [$this['name'],$this['value']]);
    }
    function beforeDelete(){
        if($this->sync)$this->cmd('unset', [$this['name']]);
    }
    function beforeModify(){
        //$this->cmd('delete', [$this['name']]);
        if($this->sync)$this->cmd('set', [$this['name'],$this['value']]);
    }
}
