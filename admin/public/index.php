<?php

chdir('..');

require_once '../vendor/autoload.php';

if (file_exists('../agiletoolkit-sandbox.phar')) {
    require_once "../agiletoolkit-sandbox.phar";
}

require_once 'lib/Admin.php';

$api = new Admin('admin');
$api->main();