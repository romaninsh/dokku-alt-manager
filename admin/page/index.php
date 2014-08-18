<?php

/**
 * Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page {

    public $title='Dashboard';

    function init() {
        parent::init();
        $this->add('View_Box')
            ->setHTML('Welcome to your new Web App Project. Get started by opening '.
                '<b>admin/page/index.php</b> file in your text editor and '.
                '<a href="http://book.agiletoolkit.org/" target="_blank">Reading '.
                'the documentation</a>.');


        $vp = $this->add('VirtualPage');





        $form=$this->add('Form');
        $f_email = $form->addField('email')->validateNotNull();

        $js_check=$this->js()->univ()->ajaxec($vp->getURL(), ['val'=>$f_email->js()->val()]);
        $f_email->afterField()->add('Button')->set(['', 'icon'=>'ellipsis'])->addClass('do-check')->js('click', $js_check);

        $f_email->js('change', $js_check);

        $vp->set(function($p)use($form){
            if(strlen($_POST['val'])>2){
                $js=[
                    $form->js()->find('.do-check span')->attr('class','icon-cancel'),
                    $form->js()->find('.do-check')->children()->removeClass('atk-effect-success')->addClass('atk-effect-danger')
                ];
            }else{
                $js=[
                    $form->js()->find('.do-check span')->attr('class','icon-check'),
                    $form->js()->find('.do-check')->children()->removeClass('atk-effect-danger')->addClass('atk-effect-success')
                ];
            }
            $form->js(null,$js)->execute();
        });



        $name = $form->addField('name')->validateNotNull();

        $form->addSubmit('Submit');

    }

}
