<?php
class page_docs extends Page {

    public $title='Documentation';

    function page_index(){
        $this->app->layout->template->tryDel('has_page_title');
        $this->add('romaninsh/mdcms/View')->set('index');
    }
    function subPageHandler($p){
        $this->app->layout->template->tryDel('has_page_title');
        $this->add('romaninsh/mdcms/View')->set($p);

    }

}
