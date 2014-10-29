<?php
/**
 * Model implementation
 */
namespace dokku_alt;
class Model_Buildpack extends \Model
{
    public $table='buildstack';
    function init()
    {
        parent::init();

        $this->addField('name');

        // cache buildstacks
        $buildstacks = $this->app->recall('buildstacks',false) ?:
            $this->app->memorize('buildstacks',trim(file_get_contents('https://raw.githubusercontent.com/progrium/buildstep/master/stack/buildpacks.txt')));

        $a = explode("\n",$buildstacks);
        $a = array_combine($a,$a);
        $this->setSource('Array', $a);
    }
}
