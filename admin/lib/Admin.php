<?php

class Admin extends App_Admin {

    function init() {
        parent::init();

        $this->api->pathfinder
            ->addLocation(array(
                'addons' => array('addons', 'vendor'),
            ))
            ->setBasePath($this->pathfinder->base_location->getPath() . '/..')
        ;

        $this->dbConnect();

        $this->api->menu->addItem(['Dashboard', 'icon'=>'home'], '/');

        $this->add('dokku_alt/Initiator');
    }
}
