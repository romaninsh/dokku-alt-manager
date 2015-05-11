<?php
/**
 * Model implementation
 */
class Model_GitHubPushLog extends SQL_Model
{
    public $table="githubpushlog";

    function init()
    {
        parent::init();

        $this->addField('timestamp');
        $this->addField('payload');
        $this->addField('message');
    }
}
