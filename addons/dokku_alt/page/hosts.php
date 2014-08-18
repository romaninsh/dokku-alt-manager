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

        $c=$this->add('Columns');
        $c1=$c->addColumn(6);
        $c2=$c->addColumn(6);

        $cr_app = $c1->add('CRUD');
        $cr_app->setModel($this->model->ref('App'));
        $cr_app->grid->addFormatter('name','link', ['page'=>'./app','id_field'=>'app_id']);

        $c2->add('CRUD')->setModel($this->model->ref('DB'));
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
        //$c2->add('CRUD')->setModel($this->m_app->ref('DB_Link'));
    }
}
