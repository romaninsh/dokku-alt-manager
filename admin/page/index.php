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

    }

}
