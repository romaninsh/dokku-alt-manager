<?php
namespace dokku_alt;
class Model_Access_Admin extends Model_Access
{
    function init()
    {
        parent::init();

        $this->addCondition('type','admin');
        $this->hasOne('dokku_alt/Host',null,null,'Host');

        $this->addHook('beforeSave,beforeDelete',$this);
    }
    function fetch()
    {
        $data = $this->ref('host_id')->executeCommand('access:list');
        $data = explode("\n", $data);
        $result = [];
        foreach($data as $row){
            $row=trim($row);
            list($type,$name) = explode("\t",$row);
            $result[$name]=['type'=>$type];
        }
        return $result;
    }
    function beforeSave(){

        // return, not ours to control
        if(!$this['is_dam_controlled'])return;

        $response = $this->ref('host_id')->executeCommandSTDIN('access:add', $this['publickey']);
        list(,$fingerprint)=explode('----->',$response);
        $fingerprint=trim($fingerprint);
        list($fingerprint)=explode(' ',$fingerprint);
        $this['fingerprint']=$fingerprint;
    }
    function beforeDelete(){
        if(!$this['is_dam_controlled'])return;

        $response = $this->ref('host_id')->executeCommand('access:remove', [$this['fingerprint']]);
    }
}
