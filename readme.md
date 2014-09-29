Welcome to Dokku Alt Manager
============================

This is a web application which will help you manage your
web host running dokku-alt (https://github.com/dokku-alt/dokku-alt) through
the comfort of your browser.

![Screenshot](/doc/screenshot.png)
![Screenshot](/doc/screenshot2.png)
![Screenshot](/doc/screenshot3.png)


Install with dokku-alt-beta
---------------------------


First, Make sure you install dokku-alt-beta on "your-dokku-host". This will not
work with "dokku". See http://dokku-alt.github.io/try-it.html

Next - upgrade to "beta" release of dokku-alt by following instructions here:
https://github.com/dokku-alt/dokku-alt#upgrade-and-beta-releases


Finally - execute the following commands:

    HOST=your-dokku-host    # Deploy to existing dokku-alt host

    ssh dokku@$HOST create dam

    ssh dokku@$HOST config:set dam BUILDPACK_URL=https://github.com/romaninsh/heroku-buildpack-php.git#development

    ssh dokku@$HOST mariadb:create dam
    ssh dokku@$HOST mariadb:link dam dam
    ssh dokku@$HOST mariadb:console dam dam < doc/schema.sql

    git clone https://github.com/romaninsh/dokku-alt-manager.git
    cd dokku-alt-manager
    git remote add deploy dokku@$HOST:dam
    git push deploy master

If everything goes smooth, this should respond with the URL you can
copy-paste into your browser.

Local Use
---------

If you are running Apache / PHP locally, create config.php file with the
following contents inside:

    <?php
    $config['dsn']='mysql://root:root@localhost/dam';

Then open "admin/public" sub-folder of this project in your web browser.
Import doc/schema.sql into your MySQL or MariaDB database.

Troubleshooting
----------------

    ssh dokku@$HOST logs dam -t

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


