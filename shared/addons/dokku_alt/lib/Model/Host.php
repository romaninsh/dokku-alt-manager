<?php
namespace dokku_alt;
class Model_Host extends \SQL_Model {
    public $table='host';

    public $packet_handler = null;

    function init(){
        parent::init();

        $this->addField('name')->mandatory(true);
        $this->addField('addr')->mandatory(true);
        $this->addField('public_key')->type('text');
        $this->addField('private_key')->type('text')->visible(false);

        $this->addField('notes')->type('text');

        $this->hasMany('dokku_alt/App',null,null,'App');
        $this->hasMany('dokku_alt/Host_Log',null,null,'Host_Log');
        $this->hasMany('dokku_alt/DB',null,null,'DB');
        $this->hasMany('dokku_alt/Access_Admin',null,null,'Access');

        $this->addField('last_synced');

        $this->addField('is_debug')->type('boolean');
    }
    function connect(){
        try {

            $ssh = new \Net_SSH2($this['addr'], $this['ssh_port'] ? : 22);
            $key=$this->getPrivateKey();

            if (!$ssh->login($this['ssh_user'] ? : 'dokku', $key)) {
                throw $this->exception('Login Failed!','Exception_ForUser');
            }
            return $ssh;
        } catch (BaseException $e) {
            throw $e; // don't do anything yet
            // var_dump($e);
        }
    }

    function getPrivateKey(){
        $key = new \Crypt_RSA();

        if($this['private_key']){
            $key->loadKey($this['private_key']);
            return $key;
        }

        // else look in keychain
        $k = $this->add('Model_Keychain');
        $k->tryLoadBy('host',$this['addr']);
        if(!$k->loaded())$k->tryLoadBy('host','*');
        if(!$k->loaded())throw $this->exception('Could not find matching private key in Keychain');


        $pack = $this->app->getPackingKey();
        if($pack)$key->setPassword($pack);

        $key->loadKey($k['data']);
        return $key;
    }

    function executeCommand($command, $args = [], \View_Console $c=null) {
        $this->ref('Host_Log')
            ->set('line',$command.' '.join(' ',$args))
            ->saveAndUnload();
        if($this['is_debug']){
            return '[debug-logged]';
        }
        $ssh=$this->connect();
        // must escape
        ///$args = array_map('escapeshellarg',$args);
        return trim($ssh->exec($command.' '.join(' ',$args),$this->packet_handler ?: ($c?function($str)use($c){$c->out($str);}:null)));
    }

    function executeCommandSTDIN($command, $stdin, $args = []) {
        $this->ref('Host_Log')
            ->set('line',$command.' '.join(' ',$args))
            ->saveAndUnload();

        if($this['is_debug']){
            return '[debug-logged]';
        }
        $ssh=$this->connect();
        $ssh->enablePTY();
        $ssh->exec($command.' '.join(' ',$args));
        $ssh->write($stdin."\n\x04");
        $ssh->setTimeout(3);
        return trim($ssh->read());
    }

    /**
     * This will attempt to retrieve all information from your host and
     * store it locally.
     */
    function sync(\View_Console $c=null){

        if($c)$c->out('Syncing applications');
        $this->syncApps($c);

        if($c)$c->out('Syncing complete');

        $this['last_synced']=$this->dsql()->expr('now()');
        $this->saveLater();
    }

    function syncApps(\View_Console $c=null)
    {
        $apps =  explode("\n",$this->executeCommand('apps:list'));

        $m_app = $this->ref('App');


        foreach($apps as $app){
            $m_app -> tryLoadBy('name',$app);
            $m_app['name'] = $app;

            //$m_app->sync($c);


            $m_app->saveAndUnload();
        }
    }

    function test(){
        return $this->executeCommand('version');
    }
}
