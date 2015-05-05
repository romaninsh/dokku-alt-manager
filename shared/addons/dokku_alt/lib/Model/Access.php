<?php
namespace dokku_alt;
class Model_Access extends Model_Abstract {
    public $table='access';
    public $title_field = 'fingerprint';

    function init()
    {
        parent::init();

        //$this->hasOne('dokku_alt/App');
        $this->addField('type');

        $this->addField('fingerprint');
        $this->addField('publickey')->type('text')->caption('Public Key');
        $this->addField('privatekey')->type('text')->hint('Optional')->caption('Private Key');

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
