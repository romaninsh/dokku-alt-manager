<?php

class Model_Keychain extends SQL_Model {

    public $table='keychain';
    public $title_field='host';

    function init() {
        parent::init();

        $this->addField('host');
        $this->addField('notes')->type('text');
        $this->addField('data')->type('text');

        $this->addField('is_secure')->type('boolean')->editable(false)
            ->hint('Will use your current passkey to encrypt');

        $this->hasOne('User')->defaultValue($this->app->auth->model->id);

        $this->addHook('beforeUpdate',array($this,'checkSecurity'));


    }

    /**
     * Attempts to use the key with current passkey thus making sure that
     * passphrase works
     */
    function verify() {
        $rsa = new Crypt_RSA();

        $rsa->loadKey($this['notes']);
        $encrypt = $rsa->encrypt('test');

        $pack = $this->app->getPackingKey();
        if($pack)$rsa->setPassword($pack);
        $rsa->loadKey($this['data']);
        $text = $rsa->decrypt($encrypt);

        // Missmatch here shouldn't happen. It would rather throw
        // exception during decrypt();
        return ($text == 'test')?'Successful':'Descryption missmatch';
    }

    /**
     * Returns Crypt_RSA object with decrypted private key.
     */
    function getPrivateKey(){
        $key = new \Crypt_RSA();

        $pack = $this->app->getPackingKey();
        if($pack)$key->setPassword($pack);

        $key->loadKey($this['data']);
        return $key;
    }

    function decryptData($key = null) {
    }

    /**
     * Generates random key with optonal passphrase and stores it
     * in the model
     */
    function generateKey($pack = null) {

        $rsa = new Crypt_RSA();

        if($pack)$rsa->setPassword($pack);

        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        $key = $rsa->createKey();

        $this['kind'] = 'key';
        $this['host'] = '*';
        $this['data'] = $key['privatekey'];
        $this['is_secure'] = (boolean) $pack;
        $this['notes'] = $key['publickey'];
    }

}
