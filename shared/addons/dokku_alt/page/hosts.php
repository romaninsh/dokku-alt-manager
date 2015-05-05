<?php
/**
 * Page class
 */
namespace dokku_alt;
class page_hosts extends Page
{
    public $title='Cloud Hosts';

    function init(){
        $this->setModel('dokku_alt/Host');
        $this->model->load($this->app->stickyGet('host_id'));
        parent::init();
    }

    function page_index()
    {
        $this->app->redirect($this->app->url('/',['host_id'=>null]));

/*
        $cr=$this->add('CRUD');
        $cr->setModel($this->model);
        $cr->addAction('test','column');

        $cr->grid->addFormatter('name','link', ['page'=>'./details','id_field'=>'host_id']);

        $p_log=$cr->addFrame('log');
        if($p_log){
            $p_log->add('Grid')->setModel($cr->model->load($cr->id)->ref('Host_Log'));
        };
        */
    }

    function page_access()
    {

        $this->title='Access for '.$this->model['name'];
        $this->addCrumb('Cloud Hosts');

        $cr=$this->add('DamCRUD');
        $cr->setModel($this->model->ref('Access'));
        $cr->addAction('generateAndadd','toolbar');

    }

    function page_details()
    {

        $this->model->load($this->app->stickyGet('host_id'));

        $this->title=$this->model['name'];
        $this->addCrumb('Cloud Hosts');


        $bs = $this->add('ButtonSet')->addClass('atk-push atk-box-small atk-move-right');
        $bs->addButton('Remove')->addClass('atk-swatch-red')
            ->js('click')
            ->univ()
            ->dialogURL('Remove Host '.$this->model['name'],$this->app->url('./remove'));


        $bs = $this->add('ButtonSet')->addClass('atk-push atk-box-small');
        $bs->addButton('Edit Details')
            ->js('click')
            ->univ()
            ->dialogURL('Edit Details for '.$this->model['name'],$this->app->url('./edit'));

        $bs->addButton('Get Dokku Version')
            ->js('click')
            ->univ()
            ->frameURL('Get Dokku Version for '.$this->model['name'],$this->app->url('./version'));

        $bs->addButton('List Plugins')
            ->js('click')
            ->univ()
            ->frameURL('List Plugins for '.$this->model['name'],$this->app->url('./plugins'));

        $bs->addButton('Get Help')
            ->js('click')
            ->univ()
            ->frameURL('Get Dokku Version for '.$this->model['name'],$this->app->url('./help'));

        $bs->addButton('Logs')
            ->js('click')
            ->univ()
            ->frameURL('Log for '.$this->model['name'],$this->app->url('./log'));






        $c=$this->add('Columns');
        $c1=$c->addColumn(6);
        $c2=$c->addColumn(6);

        $cr_app = $c1->add('CRUD');

        $b_sync = $cr_app->addButton(['Sync Apps','icon'=>'retweet']);


        $cr_app->setModel($this->model->ref('App'),['host','name','url']);
        $cr_app->grid->addFormatter('name','link', ['page'=>'./apps','id_field'=>'app_id']);
        //$cr_app->addAction('deployGitApp','toolbar');

        if(!$cr_app->isEditing()){
            $cr_app->add_button->setLabel(['Create Blank App']);
            $cr_app->grid->addButton(['Deploy Git App','icon'=>'github'])
                ->js('click')->univ()->dialogURL('Deploy Git App',$this->app->url('./git'));
        }

        $b_sync->onClick(function() use($cr_app){
            $this->model->syncApps();
            return $cr_app->js()->reload();
        });


        $c2->add('CRUD')->setModel($this->model->ref('DB'));
    }

    function page_details_git(){
        $f=$this->add('Form');
        $f->addField('line','repo','Git Repository');
        $f->addField('line','name','Name');
        $f->addField('dropdown','key','Key for Authentication')
            ->setModel('Model_Keychain');
        $f->addField('checkbox','hook','Enable automatic updates through Web Hook')
            ->setFieldHint('not implemented yet');


        $f->onSubmit(function($f){
            $app = $this->model->ref('App');
            $app['name']=$f['name'];
            $key = $f->getElement('key')->model->load($f['key']);
            $app->deployGitApp($f['name'],$f['repo'],$key);

            return $f->js()->html('<pre/>')->children()->text($app['last_build'])->univ()->successMessage('Deployed');
        });
    }

    function page_details_edit()
    {
        $this->model->load($this->app->stickyGet('host_id'));
        $f = $this->add('Form');
        $f->setModel($this->model);
        $f->onSubmit(function($f){
            $f->save();
            $f->js()->univ()->closeDialog()->location($f->app->url('..'))->execute();
        });
    }
    function page_details_remove()
    {
        $m = $this->model->load($this->app->stickyGet('host_id'));
        $this->add('View_Info')->set('Removing this host will not destroy the server or shut down any containers. It will only remove local record about this host. Continue?');
        $f = $this->add('Form');
        $f->onSubmit(function($f)use($m){
            $m->delete();
            $f->js()->univ()->closeDialog()->location($f->app->url('/'))->execute();
        });
    }

    function page_details_version()
    {
        $this->model->load($this->app->stickyGet('host_id'));
        $this->add('View')->set($this->model->updateVersion());
    }
    function page_details_plugins()
    {
        $this->model->load($this->app->stickyGet('host_id'));

        $plugin = $this->add('Model');
        $plugin->setSource('Array', explode("\n",$this->model->executeCommand('plugins')));

        $list = $this->add('CompleteLister',null,null,['lister/minitable']);
        $list->setModel($plugin);
        $list->template->set('title','Installed Plugins');
    }

    function page_details_help()
    {
        $this->model->load($this->app->stickyGet('host_id'));
        $this->add('View')->setElement('pre')->set($this->model->executeCommand('help'));
    }

    function page_details_log()
    {
        $this->model->load($this->app->stickyGet('host_id'));
        $this->add('Grid')->setModel($this->model->ref('Host_Log'))->setOrder('ts','desc');
    }


}

