ALTER TABLE `app`
ADD COLUMN `keychain_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `last_build`,
ADD COLUMN `repository` VARCHAR(255) NULL DEFAULT NULL AFTER `keychain_id`,
ADD INDEX `fk_app_keychain1_idx` (`keychain_id` ASC);

ALTER TABLE `app`
ADD CONSTRAINT `fk_app_keychain1`
  FOREIGN KEY (`keychain_id`)
  REFERENCES `dam`.`keychain` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
