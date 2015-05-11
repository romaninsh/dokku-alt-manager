<?php
/**
 * Model implementation
 */
class Model_AppTemplate extends SQL_Model
{
    public $table="app_template";

    function init()
    {
        parent::init();

        //$this->setSource('SQL');

        $this->addField('git_repository')
            ->hint('This repository must contain deployable application, possibly with Dockerfile inside');

        $this->hasOne('Keychain')
            ->hint('Use this key when pulling from repository');


        $this->addField('enable_interfaces')->type('boolean')
            ->hint('Multiple apps will be deployed, all linked to all volumes and databases');

        $this->addField('interface_config')->type('text')
            ->hint('Describes interface configuration in JSON. See Documentation.');

        $this->addField('database_config')->type('text')
            ->hint('Describes database configuration in JSON. See Documentation.');

        $this->addField('volume_config')->type('text')
            ->hint('Describes volume configuration in JSON. See Documentation.');


            // should be "hasChild"
        //$this->hasMany('AppTemplate_InterfaceConfig');
        //$this->hasMany('AppTemplate_DatabaseConfig');
        //$this->hasMany('AppTemplate_VolumeConfig');


        $this->add('dynamic_model/Controller_AutoCreator');
    }

/*
    function ref($ref){
        if($ref == 'InterfaceConfig');

    }
    */
}
