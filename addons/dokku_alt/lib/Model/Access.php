<?php
namespace dokku_alt;
class Model_Access extends  \SQL_Model {
    public $table='access';

    function init()
    {
        parent::init();

        //$this->hasOne('dokku_alt/App');
        $this->addField('type');

        $this->addField('fingerprint');
        $this->addField('publickey')->type('text');
        $this->addField('privatekey')->type('text');

    }

/*
    function beforeSave(){
        //if($this->loaded)throw $this->exception('Modification is now allowed');
        $

    }

    function cmd($command, $args=[]){
        $app=$this->ref('app_id');
        array_unshift($args, $this['name']);
        return $app->ref('host_id')->executeCommand('access:'.$command, $args);
    }
    */

    function generateAndAdd()
    {
        $rsa = new \Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        $this->set($rsa->createKey());
        $this->save();
    }
}
