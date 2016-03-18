<?php
class endpoint_1_ping extends Endpoint_REST {
    public $authenticate=false;

    function get()
    {
        return $this->outputOne(['PONG']);

    }

}
