<?php
namespace misc;
/**
 * Implements model field which is calculated by PHP, not SQL.
 * Tested with relational models only.
 *
 * Use:
 *
 * $model->add('misc/Field_Callback','myfield')->set(function($m){
 *     return $m->id * 2;
 * });
 *
 * Inside the function you can access other fields through $m['myfield'];
 */
class Field_Callback extends \Field {
    public $callback=null;
    public $initialized=false;
    function init(){
        parent::init();
        $this->editable(false);
    }
    function set($callback){
        $this->callback=$callback;
        return $this;
    }
    function updateSelectQuery($select){
        $this->initialized=true;
        $this->owner->addHook('afterLoad',$this);
    }
    function afterLoad($m){
        $result=call_user_func($this->callback,$this->owner,$this);
        $this->owner->set($this->short_name,$result);
        return $this;
    }
    function updateInsertQuery($insert){
        return $this;
    }
    function updateModifyQuery($insert){
        return $this;
    }
}
