<?php
require_once'config-default.php';
$config['url_prefix']='';
$config['dsn']=str_replace(
  ['mysql2',':3306'],
  ['mysql',''],
  $_ENV['DATABASE_URL']);
