# Dokku Alt Manager

Using Dokku Alt Manager you can significantly simplify your web app development
and management lifecycle and also do many other interesting things. This
application takes Dokku-alt to a next level by adding:

 - Extensive Web UI interface for managing your infrastructure
 - API for Github Hooks to push updates to your containers
 - Application management
 - Rest API to integrate inside your applications

## Getting Started

You will need at least one Ubuntu box to get started. Install
dokku-alt on your box, then under "Infrastructure > Hosts" set up
your new box.

If you have enabled this interface through "dokku manager:enable", then
this host will already be added for you.

Proceed to "Sync" your host, which will look for applications that you
might already have installed directly through command-line interface and
create local cache for them.

### Define Applications

You will want to define different types of applications on your dokku-alt
host. By default I included "wordpress". To add a new type, you'll need
to specify URL for the application repository.

You may then deploy this application to one or several hosts. Application
also allow you to define necessary dependencies for your application such
as database link, volume. Those will be automatically created for your
application as you deploy them.

## Security

Dokku-alt-manager relies on MariaDB database to store your infrastructure
configuration and private keys. For security purposes you may want to
enable "packing key". This will add pass-key encryption to all the private
keys but you will have to enter this packing key every time you log into
your management interface. Packing key will not be stored in the database.

## Config

Dokku-alt-manager now has a system-wide config page, which allows you
to enable or disable certain features. For example you can enable "Rest API"
there. See "Documentation" section on how to use Rest API for your custom
infrastructure management.

Additionally you may want to enable Github push-hook support. You will
have to configure GitHub with a specified URL, but this will allow your
applicatinos to be automatically re-deployed when new changes are committed
into github.
