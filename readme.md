Welcome to Dokku Alt Manager
============================

This is a web application which will help you manage your
multiple instances running dokku-alt (https://github.com/dokku-alt/dokku-alt)

![Screenshot](/doc/screenshot.png)


Install
-------

    HOST=your-dokku-host    # Deploy to existing dokku-alt host

    ssh dokku@$HOST config:set dam BUILDPACK_URL=https://github.com/romaninsh/heroku-buildpack-php.git#development

    ssh dokku@$HOST mariadb:create dam
    ssh dokku@$HOST mariadb:link dam dam
    ssh dokku@$HOST mariadb:console dam dam < doc/schema.sql

    git clone https://github.com/romaninsh/dokku-alt-manager.git
    git remote add deploy git://$HOST:dam
    git push deploy master

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

Planned Features
----------------

 - Password-protect the app
 - Passkey support for private key
 - Fetch all apps from host

 - Save and restore snapshots
 - More intuitive interface


