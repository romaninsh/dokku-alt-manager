<?php
/**
 * Page class
 */
namespace dokku_alt;
class page_hosts extends Page
{
    public $title='Cloud Hosts';
    function init()
    {
        parent::init();

        $cr=$this->add('CRUD');
        $cr->setModel('dokku_alt/Host');
        $cr->addAction('test','column');
    }
}
