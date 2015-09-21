<?php

class MDCms extends \romaninsh\mdcms\Controller {
    function addLocation() {
        $this->api->pathfinder->base_location->defineContents(array(
            'md_content'=>'../doc/markdown',
        ));
    }
}
