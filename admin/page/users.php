<?php
/**
 * Page class
 */
class page_users extends Page
{
    public $title='User Manager';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $user=$this->add('Model_User');

        if((string)$user->count() < 1) {
            $user->addHook('afterSave', function($m){
                // finalize and go logout...
                @$this->app->auth->logout(false);
                $m->_dsql()->owner->commit();
                $m->app->js()->univ()->location($this->app->url('index'))->execute();
            });
        }

        $user = $this->add('CRUD')->setModel($user);

    }
}
