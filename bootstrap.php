<?php
include'vendor/autoload.php';
$app = new App_CLI();

//$app->pathfinder->addRelativeLocation(['php','shared/lib']);

try {

    $app->add('Logger');
    $app->pathfinder->base_location->addRelativeLocation('shared',[
        'php'=>'lib',
        'addons'=>'addons',
    ]);


    if (PHP_SAPI !== 'cli' || !empty($_SERVER['REMOTE_ADDR']))
        throw new Exception('Execute from command-line only');


    $options = getopt("k:rha:n:", ['key:','reset','help','addr:','name:']);

    if(
        isset($options['h'])
        || isset($options['help'])
    ) {
        echo <<<EOF
Usage:

    php bootstrap.php [-k <key> -i <ip> [-n <name>]] [-r] [-h]

    -k, --key <file>
    -a, --addr <addr>
    -n, --name <name>

        Read private key from file and add it as default host. <addr>
        must be also specified. If you omit the name, addr will be used.

    -r, --reset

        Reset user information

    -h, --help

        Display this message

EOF;
        exit;
    }

    echo "Bootstrapping Dokku-alt-manager\n";

    // First lets test database
    echo "Connecting do database: ";
    $app->dbConnect();
    echo "OK\n";

    // See if database needs updating
    echo "Updating Database: ";
    $app->add('Controller_Migrator_MySQL')->migrate();
    echo "OK\n";

    // Should we reset users?
    if(isset($options['r']) || isset($options['reset'])){
        $app->add('Model_User')->each('delete');
        echo "User access list emptied.\n";
    }

    // Should we import key
    if(isset($options['k']) || isset($options['key'])){
        $file = $options['k'] ?: $options['key'];

        if($file == '-')$file='php://stdin';

        $addr = $options['a']?:$options['addr'];
        if(!$addr)throw $app->exception('Addr must be specified');
        $name = $options['n']?:$options['name']?:$addr;

        if(!$file || !is_readable($file)) die("Specify a valid key file\n");

        echo "Importing key from ".($file=='-'?'STDIN':$file)." for $addr ($name)\n";

        $private_key = file_get_contents($file=='-'?STDIN:$file);

        $host = $app->add('dokku_alt/Model_Host');
        $host->tryLoadBy('addr',$addr);

        $host['addr']=$addr;
        $host['name']=$name;
        $host['private_key']=$private_key;
        $host->saveAndUnload();
    }


}catch(Exception $e){
    $app->caughtException($e);
}
