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
    function beforeSave(){
        $response = $this->ref('host_id')->executeCommandSTDIN('access:add', $this['publickey']);
        list(,$fingerprint)=explode('----->',$response);
        $fingerprint=trim($fingerprint);
        list($fingerprint)=explode(' ',$fingerprint);
        $this['fingerprint']=$fingerprint;
    }
    function beforeDelete(){
        $response = $this->ref('host_id')->executeCommand('access:remove', [$this['fingerprint']]);
    }
}
