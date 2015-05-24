/* 1.0.3  Migrate */
ALTER TABLE `cot_region`
CHANGE `region_id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `region_country` `country` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
CHANGE `region_title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;

ALTER TABLE `cot_city`
CHANGE `city_id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `city_country` `country` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
CHANGE `city_region` `region` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `city_title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;

ALTER TABLE `cot_city` ADD `sort` INT UNSIGNED NULL DEFAULT '0' AFTER `region` ;