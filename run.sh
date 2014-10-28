#!/bin/bash

echo "Database=$DATABASE_URL"

source /etc/apache2/envvars
exec apache2 -D FOREGROUND
