<?php
require_once'config-default.php';
$config['url_prefix']='';
$config['production']=true;

if(isset($_ENV['DATABASE_URL'])){
    $config['dsn']=str_replace(
      ['mysql2',':3306'],
      ['mysql',''],
      $_ENV['DATABASE_URL']);
}else{
    # specify your mysql database here
    $config['dsn']='mysql://root:root@localhost/dam';
}
