<?php
// Taken from https://groups.google.com/forum/#!msg/agile-toolkit-devel/ziPpJvaEohw/ZH0Ub28noeQJ
// Credit to Janis Volbergs
// Usage: $form->add("Controller_FormAsterisk");

namespace misc;
class Controller_FormAsterisk extends \AbstractController {
    
    public $style = 'color:#e51717';
    public $text = '&nbsp;&#42;';
    
    function init(){
        parent::init();
        $this->owner->js(true)
            ->find('.atk-form-row')
            ->not('.atk-form-row-checkbox')
            ->find('.mandatory span:first')
            ->append("<span style='".$this->style."'>".$this->text."</span>");
    }
}
