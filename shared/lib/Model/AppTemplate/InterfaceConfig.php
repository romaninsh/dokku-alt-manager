<?php
/**
 * Model implementation
 */
class Model_AppTemplate_InterfaceConfig extends Model
{
    function init()
    {
        parent::init();

        $this->addField('name');
        $this->addField('document_root');
    }
}
