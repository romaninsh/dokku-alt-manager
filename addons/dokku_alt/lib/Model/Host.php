<?php
namespace dokku_alt;
class Model_Host extends \SQL_Model {
    public $table='host';

    function init(){
        parent::init();

        $this->addField('name');
        $this->addField('addr');
        $this->addField('public_key')->type('text');
        $this->addField('private_key')->type('text')->visible(false);

        $this->hasMany('dokku_alt/App',null,null,'App');
        $this->hasMany('dokku_alt/Host_Log',null,null,'Host_Log');
        $this->hasMany('dokku_alt/DB',null,null,'DB');

        $this->addField('is_debug')->type('boolean');
    }
    function connect(){
        try {

            $ssh = new \Net_SSH2($this['addr'], $this['ssh_port'] ? : 22);
            $key = new \Crypt_RSA();

            if ($key->loadKey($this['private_key']) === false) {
                throw $this->exception("Private key loading failed!");
            }

            if (!$ssh->login($this['ssh_user'] ? : 'dokku', $key)) {
                throw $this->exception('Login Failed!');
            }
            return $ssh;
        } catch (BaseException $e) {
            throw $e; // don't do anything yet
            // var_dump($e);
        }
    }

    function executeCommand($command, $args = []) {
        if($this['is_debug']){
            $this->ref('Host_Log')
                ->set('line',$command.' '.join(' ',$args))
                ->saveAndUnload();
            return '[debug-logged]';
        }
        $ssh=$this->connect();
        // must escape
        ///$args = array_map('escapeshellarg',$args);
        return trim($ssh->exec($command.' '.join(' ',$args)));
    }

    function test(){
        return $this->executeCommand('version');
    }
}
