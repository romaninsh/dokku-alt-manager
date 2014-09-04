<?php

class page_index extends Page {

    public $title='Dashboard';

    function init() {
        parent::init();
        $this->add('View_Box')
            ->setHTML('Welcome to Dokku Admin Manager. This application will help you with administration of your Web Site Hosting based around "dokku-alt" project.');


        $bs=$this->add('ButtonSet');
        $bs->addButton('Add New Host')->link($this->app->url('./add'));
        $bs->addButton('Deploy New App');
        $bs->addClass('atk-push');

        $this->add('View_ServerList')->setModel('dokku_alt/Host');
    }

}
