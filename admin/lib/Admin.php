<?php

    class Admin extends App_Admin
{
    public function init()
    {
        parent::init();

        $this->app->pathfinder->base_location
            ->addRelativeLocation('..',array(
                'md_content' => 'doc'
            ))
        ;

        $this->app->pathfinder->shared_location
            ->defineContents(['addons'=>'addons/atk4']);

        $this->template['css']='compact.css';

        $this->dbConnect();


        if($this->url('dam')->isCurrent(true)){

            $this->api->menu->addItem(['Access', 'icon'=>'users'], 'users');
            $this->api->menu->addItem(['Applications', 'icon'=>'home'], '/');
            $this->api->menu->addItem(['Deploy Keys', 'icon'=>'key'], 'keychain');
            $this->api->menu->addItem(['Dokku-Manager UI', 'icon'=>'mouse'], 'keychain');
            $this->api->menu->addItem(['Databases', 'icon'=>'database'], 'database');
            $this->api->menu->addItem(['Volumes', 'icon'=>'drive'], 'disk');

        }elseif($this->url('docs')->isCurrent(true)){
            $this->api->menu->addItem('Getting Started', 'docs');
            $this->api->menu->addItem('Rest API', 'docs/restapi');
        }else{
            $this->layout->template->tryDel('has_main_menu');
        }

/*
        */


        $user_menu = $this->layout->add('Menu_Horizontal',null,'User_Menu');
        $user_menu->addItem(['Logout', 'icon'=>'logout'],'logout');



        $this->add('dokku_alt/Initiator');

        $auth = $this->add('Auth');
        $user = $auth->setModel('User');
        $auth->usePasswordEncryption();
        if (((string) $user->count())>0) {
            $auth->check();
        } else {
            $this->layout->add('View_Error')->set('No user accounts found. Please define them before continuing.');
        }
    }

    function initTopMenu() {
        $m=$this->layout->add('Menu_Horizontal',null,'Top_Menu');
        $m->addItem(['Infrastructure', 'icon'=>'network'],'/infra');
        $m->addItem(['Applications', 'icon'=>'box-1'],'/apps');
        $m->addItem(['Configuration', 'icon'=>'cog'],'/config');
        $m->addItem(['Documentation', 'icon'=>'book'],'/docs');
    }


    /**
     * Packing key is a passphrase for your keychain. It helps you to unlock
     * passphrase-protected keys
     */
    function getPackingKey() {
        return $this->recall('packing_key',null);
    }

}
/*
    apps:disable <app>                              Disable specific app
    apps:enable <app>                               Re-enable specific app
    apps:list                                       List app
    apps:restart <app>                              Restart specific app (not-redeploy)
    apps:start <app>                                Stop specific app
    apps:status <app>                               Status of specific app
    apps:stop <app>                                 Stop specific app
    apps:top <app> [args...]                        Show running processes
    backup:export [file]                            Export dokku configuration files
    backup:import [file]                            Import dokku configuration files
    config <app>                                    display the config vars for an app
    config:get <app> KEY                            display a config value for an app
    config:set <app> KEY1=VALUE1 [KEY2=VALUE2 ...]  set one or more config vars
    config:unset <app> KEY1 [KEY2 ...]              unset one or more config vars
    delete <app>                                    Delete an application
    domains:get <app>                               Get domains for an app
    domains:redirect:get <app>                      Get redirect domains for an app
    domains:redirect:set <app> <domains...>         Set redirect app domains
    domains:set <app> <domains...>                  Set app domains
    help                                            Print the list of commands
    logs <app> [-t]                                 Show the last logs for an application (-t follows)
    mariadb:console <app> <db>                      Launch console for MariaDB container
    mariadb:create <db>                             Create a MariaDB database
    mariadb:delete <db>                             Delete specified MariaDB database
    mariadb:info <app> <db>                         Display application informations
    mariadb:link <app> <db>                         Link database to app
    mariadb:list <app>                              List linked databases
    mariadb:unlink <app> <db>                       Unlink database from app
    mongodb:console <app> <db>                      Launch console for MongoDB container
    mongodb:create <db>                             Create a MongoDB database
    mongodb:delete <db>                             Delete specified MongoDB database
    mongodb:info <app> <db>                         Display application informations
    mongodb:link <app> <db>                         Link database to app
    mongodb:list <app>                              List linked databases
    mongodb:unlink <app> <db>                       Unlink database from app
    plugins-install                                 Install active plugins
    plugins                                         Print active plugins
    postgresql:console <app> <db>                   Launch console for PostgreSQL container
    postgresql:create <db>                          Create a PostgreSQL database
    postgresql:delete <db>                          Delete specified PostgreSQL database
    postgresql:info <app> <db>                      Display application informations
    postgresql:link <app> <db>                      Link database to app
    postgresql:list <app>                           List linked databases
    postgresql:unlink <app> <db>                    Unlink database from app
    preboot:cooldown:time <app> <secs>              Re-enable specific app
    preboot:disable <app>                           Stop specific app
    preboot:enable <app>                            Stop specific app
    preboot:status <app>                            Status of specific app
    preboot:wait:time <app> <secs>                  Restart specific app (not-redeploy)
    rebuild:all                                     Rebuild all apps
    rebuild <app>                                   Rebuild an app
    redis:create <app>                              Create a Redis database
    redis:delete <app>                              Delete specified Redis database
    redis:info <app>                                Display application information
    run <app> <cmd>                                 Run a command in the environment of an application
    tag:add <app> <tag>                             Tag latest running image using specified name
    tag:list <app>                                  List all image tags
    tag:rm <app> <tag>                              Tag latest running image using specified name
    url <app>                                       Show the URL for an application
    version                                         Print dokku's version
    volume:create <name> <paths...>                 Create a data volume for specified paths
    volume:delete <name>                            Delete a data volume
    volume:info <name>                              Display volume information
    volume:link <app> <name>                        Link volume to app
    volume:list:apps <name>                         Display apps linked to volume
    volume:list                                     List volumes
    volume:unlink <app> <name>                      Unlink volume from app
*/
