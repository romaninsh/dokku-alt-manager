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

        $this->hasOne('dokku_alt/Buildpack','buildpack_url',false,'Buildpack');

        $this->addHook('beforeSave,beforeInsert,afterSave',$this);
        //$this->addHook('afterSave',$this);
//        $this->addHook('afterInsert',$this);


        $this->hasMany('dokku_alt/Config',null,null,'Config');
        $this->hasMany('dokku_alt/Domain',null,null,'Domain');
        $this->hasMany('dokku_alt/DB_Link',null,null,'DB_Link');
        $this->hasMany('dokku_alt/Access_Deploy',null,null,'Access');
    }

    private $noexec=false;
    function beforeSave(){
        if(!$this->id)return;
        if($this->noexec)return;
        if($this->isDirty('is_started')){
            if($this['is_started']){
                $this->start();
            }else{
                $this->stop();
            }
        }
        if($this->isDirty('is_enabled')){
            if($this['is_enabled']){
                $this->enable();
            }else{
                $this->disable();
            }
        }


        if($this['is_started']===null){
            $this['is_started'] = explode(' ',$this->cmd('status'))[2] == 'running.';
        }
        if(!$this['url']){
            $this['url'] = $this->getURL();
        }
    }
    function beforeInsert(){
        $this->ref('host_id')->executeCommand('create',[$this['name']]);
    }
    function afterSave(){
        $this->ref('Config')->tryLoadBy('name','BUILDPACK_URL')->set(['name'=>'BUILDPACK_URL', 'value'=>$this['buildpack_url'] ]) ->save();
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

    function top(){
        return $this->cmd('top');
    }
    function disable(){
        $ret = $this->cmd('disable');
        return $ret;
    }
    function enable(){
        $ret = $this->cmd('enable');
        return $ret;
    }
    function start(){
        $ret = $this->cmd('start');
        return $ret;
    }
    function stop(){
        $ret = $this->cmd('stop');
        return $ret;
    }
    function getURL(){
        return $this->ref('host_id')->executeCommand('url', [$this['name']]);
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

    function deployGitApp($name, $repository)
    {
        $host = $this->ref('host_id');

        // TODO improve security
        $f=fopen('../tmp/tmpkey','w+');
        fputs($f,$host['private_key']);
        fclose($f);

        $p=$this->add('System_ProcessIO')
            ->exec('ssh-agent bash')
            ->write('cd ../tmp')
            ->write('chmod 600 tmpkey')
            ->write('ssh-add tmpkey')
            ->write('rm -rf app')
            ->write('mkdir '.$name)
            ->write('cd '.$name)
            ->write('git clone '.$repository.' .')
            ->write('git remote add deploy dokku@'.$host['addr'].':'.$name)
            ->writeAll('git push deploy master');
        $out=$p->readAll('err');


        unlink('../tmp/tmpkey');

        $this['name']=$name;
        $this['url']='http://'.$name.'.'.$host['addr'].'/';
        $this['last_build'] = $out;
        $this->noexec=true;

        $this->save();

        $this->noexec=false;


        return 'Deployed to '.$this['url'];
    }



}
