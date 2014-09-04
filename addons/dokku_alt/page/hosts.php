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
        parent::init();
    }

    function page_index()
    {


        $cr=$this->add('CRUD');
        $cr->setModel($this->model);
        $cr->addAction('test','column');

        $cr->grid->addFormatter('name','link', ['page'=>'./details','id_field'=>'host_id']);

        $p_log=$cr->addFrame('log');
        if($p_log){
            $p_log->add('Grid')->setModel($cr->model->load($cr->id)->ref('Host_Log'));
        };
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
        $cr_app->setModel($this->model->ref('App'));
        $cr_app->grid->addFormatter('name','link', ['page'=>'./app','id_field'=>'app_id']);

        $c2->add('CRUD')->setModel($this->model->ref('DB'));
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
        $this->add('View')->set($this->model->executeCommand('version'));
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

    function page_details_app()
    {
        $this->model->load($this->app->stickyGet('host_id'));
        $this->m_app=$this->model->ref('App')->load($this->app->stickyGet('app_id'));

        $this->title=$this->m_app['name'];
        $this->addCrumb($this->model['name']);
        $this->addCrumb('Cloud Hosts');

        $c=$this->add('Columns');
        $c1=$c->addColumn(6);
        $c2=$c->addColumn(6);

        //$cr_app->setModel($this->model->ref('App'));
        //$cr_app->grid->addFormatter('name','link', ['page'=>'./app','id_field'=>'app_id']);
        $c1->add('CRUD')->setModel($this->m_app->ref('Domain'));

        $c2->add('CRUD')->setModel($this->m_app->ref('Config'));

        $cr=$c2->add('CRUD');
        $cr->setModel($this->m_app->ref('Access'));
        $cr->addAction('generateAndadd','toolbar');
        //$c2->add('CRUD')->setModel($this->m_app->ref('DB_Link'));
    }
}

