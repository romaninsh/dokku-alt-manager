<?php

class View_ServerList extends CompleteLister {

    public $pop = null; // Specify popup menu for cog element
    public $pop_width = null;

    function formatRow() {

        $this->current_row_html['labels'] =
            ($this->model['is_debug']?'<span class="atk-label atk-effect-info">DEBUG</span>':'')
            .
            ($this->model['is_prefered']?'<span class="atk-label atk-effect-info">Preferred</span>':'')
            .
            ($this->model['is_scrap']?'<span class="atk-label atk-effect-danger">Scrap</span>':'')
            ;

        $this->current_row_html['link'] = $this->app->url('dam/hosts/details',['host_id'=>$this->model->id]);
        $this->current_row_html['key-link'] = $this->app->url('dam/hosts/access',['host_id'=>$this->model->id]);

        //$b=$this->owner->add('Button')->set('Ping');
        //this->current_row_html['buttons']=$b->getHTML();


        return parent::formatRow();

    }

    function init() {
        parent::init();

        if($this->pop){
            if($this->pop instanceof jQuery_Chain) {
                $this->on('click','.do-cog',$this->pop);
            }else{
                $this->on('click','.do-cog',$this->pop->showjS(array(
                    'width'=>$this->pop_width?:450,'my'=>'right top','at'=>'right+5 bottom+5','arrow'=>'vertical top right'
                )));
            }
        }else{
            $this->template->tryDel('cog');
        }
    }

    function defaultTemplate() {
        return array('view/serverlist');
    }
}

