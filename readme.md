[![Stories in Ready](https://badge.waffle.io/romaninsh/dokku-alt-manager.png?label=ready&title=Ready)](https://waffle.io/romaninsh/dokku-alt-manager)
Welcome to Dokku Alt Manager
============================

Manage your dokku-alt (https://github.com/dokku-alt/dokku-alt) through
the comfort of your browser.

![Screenshot](/doc/screenshot.png)
![Screenshot](/doc/screenshot2.png)
![Screenshot](/doc/screenshot3.png)


Install with dokku-alt
----------------------

The recommended way to install dokku-alt-manager is by using dokku-alt. You
deploy application to dokku-alt using "GIT PUSH", so you will need to
checkout this repository locally then "PUSH" it.

1. Install dokku-alt on Ubuntu by following instruction here: http://dokku-alt.github.io/try-it.html

2. Clone this repository locally.

3. Open terminal, CD to repository and type this:

    HOST=your-dokku-host    # Deploy to existing dokku-alt host

    ssh dokku@$HOST create dam
    ssh dokku@$HOST mariadb:create dam
    ssh dokku@$HOST mariadb:link dam dam

    git remote add deploy dokku@$HOST:dam
    git push deploy master

The push command will respond with the URL you can open and use.

Local Installation (for development)
---------

1. If you do not have composer, install it:

    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

2. Copy config-default.php to config.php, then edit it

3. In terminal run:

    composer install
    php bootstrap.php

This will install database and you can start using Dokku-alt by opening
"admin/public" sub-folder of this project in your web browser.

Troubleshooting
----------------

It's always good to see logs:

    ssh dokku@$HOST logs dam -t

You can also run

    ssh dokku@HOST enter dam

    tail /var/log/apache2/*

You can also run bootstrap.php with arguments, for more info:

    php bootstrap.php -h


Features
--------

 - Support for multiple dokku hosts
 - Start, stop, enable, disable apps
 - Edit app config
 - Create and link apps with databases
 - Manage volumes and link with app
 - Password-protect the app
 - Support for all basic features of Dokku-alt

Planned Features
----------------

 - Passkey support for private key
 - Fetch all apps from host

 - Save and restore snapshots


