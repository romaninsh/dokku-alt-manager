alter table `access` add `is_dam_controlled` bool;
ALTER TABLE `host` ADD `dokku_version` VARCHAR(30)  NULL  DEFAULT NULL  AFTER `is_debug`;
ALTER TABLE `config` ADD `is_dam_controlled` BOOL  NULL  DEFAULT NULL   AFTER `app_id`;
