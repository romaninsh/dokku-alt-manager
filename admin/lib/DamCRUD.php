<?php

class DamCRUD extends CRUD {
    public $allow_edit = false;
    public function setModel($model, $fields = null, $grid_fields = null)
    {
        $m = parent::setModel($model, $fields, $grid_fields);

        if($m->hasMethod('sync')){
            // icon = retweet
            $this->addAction('sync','toolbar');
        }

        if($p=$this->addFrame('Details',['icon'=>'info-circled'])){
            $p->add('View_ModelDetails')->setModel($this->model)->load($this->id);

        }

    }
}
