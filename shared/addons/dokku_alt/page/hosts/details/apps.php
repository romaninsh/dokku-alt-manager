<?php
/**
 * Page class for application management
 */
namespace dokku_alt;
class page_hosts_details_apps extends Page
{
    public $title='Cloud Hosts';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $this->m_host = $this->add('dokku_alt/Model_Host')->load($this->app->stickyGET('host_id'));
        $this->setModel($this->m_host->ref('App')->load($this->app->stickyGET('app_id')));

        $this->title=$this->model['name'];
        $this->addCrumb($this->m_host['name']);
        $this->addCrumb('Cloud Hosts');

    }
    function page_index()
    {
        /*
        $bs = $this->add('ButtonSet')->addClass('atk-push atk-box-small atk-move-right');
        $bs->addButton('Remove')->addClass('atk-swatch-red')
            ->js('click')
            ->univ()
            ->dialogURL('Remove Host '.$this->model['name'],$this->app->url('./remove'));

*/

        $bs = $this->add('ButtonSet')->addClass('atk-push atk-box-small');
        if($this->model['url'])$bs->addButton('Open')
            ->setElement('a')
            ->setAttr('href', $this->model['url'])
            ->setAttr('target','_blank')
            ->addClass('atk-swatch-green')
            ;

        if($this->model['repository']){
            $bs->addButton('Upgrade')
                ->addClass('atk-swatch-green')
                ->js('click')->univ()->dialogURL('Upgrading from Repository',$this->app->url('./upgrade'));
        }

        $bs->addButton('Edit Details')
            ->js('click')
            ->univ()
            ->dialogURL('Edit Details for '.$this->model['name'],$this->app->url('./edit'));

        $bs->addButton('Process Top')
            ->js('click')
            ->univ()
            ->dialogURL('Process Top for '.$this->model['name'],$this->app->url('./top'));

        $bs->addButton('Logs')
            ->js('click')
            ->univ()
            ->dialogURL('Logs for '.$this->model['name'],$this->app->url('./logs'));

        $bs->addButton('Rebuild')
            ->js('click')
            ->univ()
            ->dialogURL('Rebuilding '.$this->model['name'],$this->app->url('./rebuild'));

        $c=$this->add('Columns');
        $c1=$c->addColumn(6);
        $c2=$c->addColumn(6);

        //$cr_app->setModel($this->model->ref('App'));
        //$cr_app->grid->addFormatter('name','link', ['page'=>'./app','id_field'=>'app_id']);
        $c1->add('CRUD')->setModel($this->model->ref('DB_Link'));

        $c1->add('CRUD')->setModel($this->model->ref('Domain'));

        $c2->add('DamCRUD')->setModel($this->model->ref('Config'));

        $cr=$c2->add('CRUD');
        $cr->setModel($this->model->ref('Access'),['publickey','privatekey'],['fingerprint']);
        $cr->addAction('generateAndadd','toolbar');
        //$c2->add('CRUD')->setModel($this->m_app->ref('DB_Link'));
    }

    function page_upgrade()
    {
        $this->model->pullPush();
        $this->add('View')->setElement('pre')->set($this->model['last_build']);
    }

    function page_edit()
    {
        $f = $this->add('View_ModelDetails');
        $this->model->removeElement('last_build');
        $f->setModel($this->model);

        $this->add('View')->setElement('pre')->set($this->model['last_build']);
    }

    function page_top(){
        $plugin = $this->add('Model');
        $this->add('View')->setElement('pre')->set($this->m_host->executeCommand('apps:top', [$this->model['name']]));
    }
    function page_logs(){
        $plugin = $this->add('Model');
        $this->add('View')->setElement('pre')->set($this->m_host->executeCommand('logs', [$this->model['name']]));
    }
    function page_rebuild(){
        $plugin = $this->add('Model');
        $this->add('View')->setElement('pre')->set($this->m_host->executeCommand('rebuild', [$this->model['name']]));
    }
}
