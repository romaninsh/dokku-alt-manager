#!/bin/bash
cd /app
php bootstrap.php
source /etc/apache2/envvars
exec apache2 -D FOREGROUND
