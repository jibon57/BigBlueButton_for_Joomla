CREATE TABLE IF NOT EXISTS `#__bigbluebutton_meeting` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`attendeepw` VARCHAR(10) NOT NULL DEFAULT '',
	`branding` INT(1) NOT NULL DEFAULT 0,
	`copyright` VARCHAR(50) NULL DEFAULT '',
	`description` TEXT NOT NULL,
	`duration` INT(7) NOT NULL DEFAULT 0,
	`enable_htmlfive` INT NOT NULL DEFAULT 0,
	`join_url` VARCHAR(255) NOT NULL DEFAULT '',
	`logo` VARCHAR(50) NULL DEFAULT '',
	`maxparticipants` INT(7) NOT NULL DEFAULT 0,
	`meetingid` VARCHAR(10) NOT NULL DEFAULT '',
	`moderatorpw` VARCHAR(10) NOT NULL DEFAULT '',
	`record` INT(1) NOT NULL DEFAULT 0,
	`title` VARCHAR(50) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `idx_meetingid` (`meetingid`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_title` (`title`),
	KEY `idx_alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__bigbluebutton_event` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`custom_event_pass` VARCHAR(100) NOT NULL DEFAULT '',
	`event_des` TEXT NOT NULL,
	`event_end` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
	`event_password` INT NOT NULL DEFAULT 1,
	`event_start` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
	`event_timezone` VARCHAR(100) NOT NULL DEFAULT '',
	`event_title` VARCHAR(50) NOT NULL DEFAULT '',
	`join_url` VARCHAR(255) NOT NULL DEFAULT '',
	`meeting_id` INT(1) NOT NULL DEFAULT 0,
	`timezone` INT(1) NOT NULL DEFAULT 1,
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_event_title` (`event_title`),
	KEY `idx_meeting_id` (`meeting_id`),
	KEY `idx_alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;



--
-- Always insure this column rules is large enough for all the access control values.
--
ALTER TABLE `#__assets` CHANGE `rules` `rules` MEDIUMTEXT NOT NULL COMMENT 'JSON encoded access control.';

--
-- Always insure this column name is large enough for long component and view names.
--
ALTER TABLE `#__assets` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The unique name for the asset.';
