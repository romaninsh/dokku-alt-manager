#!/bin/bash
cd /app
mkdir tmp
chown www-data tmp
php bootstrap.php
source /etc/apache2/envvars
exec apache2 -D FOREGROUND
