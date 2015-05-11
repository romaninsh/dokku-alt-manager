<?php
class Model_Config extends SQL_Model
{
    public $table="global_config";

    function init()
    {
        parent::init();

        $this->addField('is_github_push_enabled')->type('boolean')
            ->caption('Enable GitHub Push Endpoint')
            ->hint('When enabled, see "GitHub Push" tab for further configuration');
        $this->addField('is_restapi_enabled')->type('boolean')
            ->caption('Enable RestAPI')
            ->hint('When enabled, see "RestAPI" tab for further configuration');

        $this->addField('rest_api_shared_secret');


        $this->add('dynamic_model/Controller_AutoCreator');
    }
}
