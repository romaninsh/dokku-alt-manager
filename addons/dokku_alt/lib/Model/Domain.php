<?php
namespace dokku_alt;
class Model_Domain extends \SQL_Model {
    public $table='domain';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/App');

        $this->addField('is_redirect')->type('boolean');

        $this->addField('name');

        $this->addHook('afterSave,afterDelete',[$this,'setDomains']);
    }
    function cmd($command, $args=[], $is_redirect=false){

        $app = $this->ref('app_id');
        $host = $app->ref('host_id');

        return $host->executeCommand('domains:'.($is_redirect?'redirect:':'').$command, array_merge([$app['name']], $args));
    }

    /**
     * Configure application with requested domains
     */
    function setDomains(){
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
