<?php
namespace dokku_alt;
class Model_App extends  \SQL_Model {
    public $table='app';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/Host');
        $this->addField('name');
        $this->addField('url');

        $this->addField('last_build')->type('text')->system(true);
        $this->addField('is_started')->type('boolean');
        $this->addField('is_enabled')->type('boolean')->defaultValue(true);

        $this->hasMany('dokku_alt/Config',null,null,'Config');
        $this->hasMany('dokku_alt/Domain',null,null,'Domain');
        $this->hasMany('dokku_alt/DB_Link',null,null,'DB_Link');
        $this->hasMany('dokku_alt/Access_Deploy',null,null,'Access');
    }

    private $noexec=false;
    function beforeSave(){
        if(!$this->id)return;
        if($this->noexec)return;
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

    /**
     * Creates empty application
     */
    function create($name)
    {
        $this['name']=$name;
        $this->ref('host_id')->executeCommand('create',[$this['name']]);
        $this->save();
    }

    function top(){
        return $this->cmd('top');
    }
    function disable(){
        $ret = $this->cmd('disable');
        $this['is_enabled']=false;
        $this->save();
        return $ret;
    }
    function enable(){
        $ret = $this->cmd('enable');
        $this['is_enabled']=true;
        $this->save();
        return $ret;
    }
    function start(){
        $ret = $this->cmd('start');
        $this['is_started']=true;
        $this->save();
        return $ret;
    }
    function stop(){
        $ret = $this->cmd('stop');
        $this['is_started']=false;
        $this->save();
        return $ret;
    }
    function getURL(){
        $this['url']=$this->ref('host_id')->executeCommand('url', [$this['name']]);
        $this->save();
        return $this['url'];
    }

    function pullPush() {
        $host = $this->ref('host_id');

        // TODO improve security
        $f=fopen('../tmp/tmpkey','w+');
        fputs($f,$host['private_key']);
        fclose($f);

        $p=$this->add('System_ProcessIO')
            ->exec('ssh-agent bash')
            ->write('cd ../tmp')
            ->write('ssh-add tmpkey')
            ->write('cd '.$name)
            ->write('git pull origin master')
            ->writeAll('git push deploy master');
        $out=$p->readAll('err');

        unlink('../tmp/tmpkey');

    }

    function deployGitApp($name, $repository, $deploy_key = null)
    {
        $host = $this->ref('host_id');
        $key = $host->getPrivateKey();
        $key->setPassword(); // clear password


        // If key for the app repository is necessary, let's also extract it

        $p=$this->add('System_ProcessIO')
            ->exec('ssh-agent bash')
            ->write('cd ../tmp')
            ;

        // TODO improve security
        $f=fopen('../tmp/hostkey','w+');
        fputs($f,$key->getPrivateKey());
        fclose($f);

        $p
            ->write('chmod 600 hostkey')
            ->write('ssh-add hostkey')
            ;

        if($deploy_key){
            $key = $deploy_key->getPrivateKey();
            $key->setPassword(); // clear password

            $f=fopen('../tmp/gitkey','w+');
            fputs($f,$key->getPrivateKey());
            fclose($f);

            $p
                ->write('chmod 600 gitkey')
                ->write('ssh-add gitkey')
                ;

        }

        $p
            ->write('rm -rf app')
            ->write('mkdir '.$name)
            ->write('cd '.$name)
            ->write('git clone '.$repository.' .')
            ->write('git remote add deploy dokku@'.$host['addr'].':'.$name)
            ->writeAll('git push deploy master');
        $out=$p->readAll('err');


        //unlink('../tmp/tmpkey');

        $this['name']=$name;
        $this['url']='http://'.$name.'.'.$host['addr'].'/';
        $this['last_build'] = $out;
        $this->noexec=true;

        $this->save();

        $this->noexec=false;


        return 'Deployed to '.$this['url'];
    }



}
