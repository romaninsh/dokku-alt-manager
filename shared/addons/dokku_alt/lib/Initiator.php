<?php
namespace dokku_alt;
class Initiator extends \Controller_Addon {
    public $addon_name='dokku_alt';
    function init(){
        parent::init();

        if(!$this->app instanceof \App_Admin)retrun;

        $this->app->dam=$this;

        $this->initMenu();
        $this->app->routePages('dam','dokku_alt');
    }
    function initMenu(){

        return;

        $m = $this->app->layout->menu->addMenu(['Cloud Config','icon'=>'cloud']);
        $m->addItem(['Apps','icon'=>'rocket-1'],'dam/apps');
        $m->addItem(['Domains','icon'=>'bookmarks'],'dam/domains');
        $m->addItem(['Databases','icon'=>'database'],'dam/db');
        $m->addItem(['Volumes','icon'=>'book'],'dam/volumes');
        $m->addItem(['Hosts','icon'=>'box'],'dam/hosts');
    }
}
