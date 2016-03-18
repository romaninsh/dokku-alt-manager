<?php
namespace dokku_alt;
class Model_Domain extends \SQL_Model {
    public $table='domain';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/App');

        $this->addField('is_redirect')->type('boolean');

        $this->addField('name');

        //$this->addHook('afterSave,afterDelete',[$this,'setDomains']);
    }
    function cmd($command, $args=[], $is_redirect=false){

        $app = $this->ref('app_id');
        $host = $app->ref('host_id');

        return $host->executeCommand('domains:'.($is_redirect?'redirect:':'').$command, array_merge([$app['name']], $args));
    }

    function sync(\View_Console $c=null){


        $domains = explode(' ',$d=$this->cmd('get'));
        $c->out('Saving Domains: '.$d);

        foreach($domains as $domain)if($domain){
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

    /**
     * Configure application with requested domains
     */
    function upload(\View_Console $c=null){
        if($c)$c->out('Setting dokku alias/redirect settings');
        $domain_alias = $domain_redirect =[];
        foreach($this as $domain){
            if ($domain['is_redirect']) {
                $domain_redirect[] = $domain['name'];
            } else {
                $domain_alias[] = $domain['name'];
            }
        }
        $this->cmd('set', $domain_alias);
        $this->cmd('set', $domain_redirect, true);
        return $this;
    }
}
