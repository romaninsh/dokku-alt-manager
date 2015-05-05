<?php
namespace dokku_alt;
/**
 * This class implements abstract mirrored
 */
class Model_Abstract extends \SQL_Model {

    //abstract function fetch();

    function init(){
        parent::init();
        $this->addField('is_dam_controlled')->type('boolean')->defaultValue(true)->system(true);

        $this->addHook('afterLoad',function($m){
            if($m['must_fetch']){
               // var_dump('must fetch');
               // exit;
            }

        });
    }


    /**
     * Retrieve the list from the host and save / update local entries. This
     * will also remove any local entries.
     */
    function sync(){

        $list=$this->fetch();

        foreach ($this as $m){

            if(!isset($list[$m[$this->title_field]])){
                // no longer on the server
                $m->delete();
                continue;
            }

            // update properties
            $m->set($list[$m[$this->title_field]]);
            $m->saveAndUnload();
            unset($list[$m[$this->title_field]]);
            continue;
        }

        foreach($list as $name=>$data){
            $this->unload()->set($data);
            $this['is_dam_controlled'] = false;
            $this[$this->title_field] = trim($name);
            $this->saveAndUnload();
        }
    }
}
