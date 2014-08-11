<?php
namespace dokku_alt;
class Model_Host_Log extends \SQL_Model {
    public $table='host_log';

    function init(){
        parent::init();

        $this->hasOne('dokku_alt/Host');

        $this->addField('ts')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
        $this->addField('line');
    }
}
