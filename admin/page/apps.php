<?php
/**
 * Page class
 */
class page_apps extends Page
{
    public $title='Applications';

    function page_index()
    {

        $this->add('View_Hint')->set('Adding applications below will not cause any change to your servers.');

        $cr = $this->add('CRUD');
        $cr->setModel('AppTemplate');
        $cr->addRef('AppTemplate_InterfaceConfig');

    }
}
