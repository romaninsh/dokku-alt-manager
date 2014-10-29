#!/bin/bash

test -f config.php || cp config-dist.php config.php
mkdir tmp
chmod 777 tmp
