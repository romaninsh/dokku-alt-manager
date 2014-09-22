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
        $app = $this->ref('app_id');
        $response = $app->ref('host_id')->executeCommandSTDIN('deploy:allow', $this['publickey'], [$app['name']]);
        list(,$fingerprint)=explode('----->',$response);
        $fingerprint=trim($fingerprint);
        list($fingerprint)=explode(' ',$fingerprint);
        $this['fingerprint']=$fingerprint;
    }
    function beforeDelete(){
        $app = $this->ref('app_id');
        $response = $app->ref('host_id')->executeCommand('access:revoke', [$app['name'], $this['fingerprint']]);
    }
}
