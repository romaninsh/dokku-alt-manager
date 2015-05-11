<?php

class page_index extends Page {

    public $title='Dokku Alt Manager';

    function init() {
        parent::init();
        $this->app->layout->template->tryDel('has_page_title');

        $this->add('romaninsh/mdcms/View')->set('index');
    }

}
