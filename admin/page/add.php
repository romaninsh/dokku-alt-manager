<?php
/**
 * Page class
 */
class page_add extends Page
{
    public $title='Add Host';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $this->app->layout->template->tryDel('has_title');

        $this->add('H2')->set('Step 1: Install Dokku-alt');
        $this->add('View_Info')->setHTML('You will need to launch a new host first and install Dokku-alt on that host. <a href="https://github.com/dokku-alt/dokku-alt#requirements" target="_blank">Dokku-alt Installation Instructions</a>');


        $this->add('H2')->set('Step 2: Fill out this form');
        $form = $this->add('Form');
        $form->setModel('dokku_alt/Host',['addr','name','private_key']);
        $form->getElement('addr')->setFieldHint('mysite.example.com');
        $form->getElement('private_key')->setFieldHint('If you leave this field empty, the application will find appropriate key in Keychain');
        $form->addSubmit('Continue');
        $form->addButton('Back')->link($this->app->url('/'));

        $form->onSubmit(function($f){
            $f->update();
            try {
                $version = $f->model->executeCommand('version');
            } catch (Exception $e){
                $f->model->delete();
                return $f->error('addr', $e->getMessage());
            }
            if(!$version){
                $f->model->delete();
                return $f->error('addr', 'Could not connect or dokku-alt was not installed');
            }
            return 'Added new host with version '.$version;
        });

    }
}
