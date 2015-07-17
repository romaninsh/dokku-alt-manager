<?php
/**
 * Model implementation
 */
namespace dokku_alt;
class Model_DB_Link extends \SQL_Model
{
    public $table="db_link";
    public $sync = true;

    function init()
    {
        parent::init();

        $this->hasOne('dokku_alt/DB',null,null,'DB');
        $this->hasOne('dokku_alt/App',null,null,'App');

        $this->addHook('beforeSave,beforeDelete', $this);
    }

    function sync(){

        $app = $this->add('dokku_alt/Model_App','model')->load($this['app_id']);
        $host = $app->ref('host_id');

        $list=[];

        echo "Fetching mariadb links\n";flush();
        $list['mariadb'] = $host->executeCommand('mariadb:list', [$app['name']]);
        echo "Fetching mysql links\n";flush();
        $list['postgresql'] = $host->executeCommand('postgresql:list', [$app['name']]);

        /* Inconsistent output

echo "       Host: redis"
echo "       Port: 6379"
echo
echo "       REDIS_URL=redis://redis:6379"

        echo "Fetching redis links\n";flush();
        $list['redis'] = $host->executeCommand('redis:info', [$app['name']]);
         */

        echo "Fetching mongodb links\n";flush();
        $list['mongodb'] = $host->executeCommand('mongodb:list', [$app['name']]);

        var_dump($list);

        echo "Saving Links\n";flush();

        foreach($list as $type=>$dbs)if($dbs){

        $db = $this->add('dokku_alt/Model_DB',['sync'=>false]);
            $db->addCondition('type',$type);

            foreach(explode(' ',$dbs) as $db){
                $db->tryLoadBy('name', $db);

                if(!$db->loaded())$db->save();

            }


            $this->tryLoadBy('name', $domain);
            $this['name']=$domain;
            $this['is_redirect']=false;
            $this->saveAndUnload();
        }

        $domains = explode(' ',$d=$this->cmd('redirect:get'));
        $c->out('Saving Redirects: '.$d);

        foreach($domains as $domain)if($domain){
            $this->tryLoadBy('name', $domain);
            $this['name']=$domain;
            $this['is_redirect']=true;
            $this->saveAndUnload();
        }
    }

    function beforeSave(){
        // create link
        if(!$this->sync)return;
        if($this->id)return;

        $db=$this->ref('db_id');
        $db->cmd('link', [$this->ref('app_id')->get('name')]);
    }
    function beforeDelete(){
        if(!$this->sync)return;
        $db=$this->ref('db_id');
        $db->cmd('unlink', [$this->ref('app_id')->get('name')]);

    }
}
