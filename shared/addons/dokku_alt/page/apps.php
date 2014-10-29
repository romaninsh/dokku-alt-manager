<?php
namespace dokku_alt;
class page_apps extends Page
{
    public $title='Cloud Apps';
    function init()
    {
        parent::init();

        $cr=$this->add('CRUD');
        $cr->setModel('dokku_alt/App');
        $cr->addAction('top','column');
        $cr->addAction('discover','column');
    }
}
