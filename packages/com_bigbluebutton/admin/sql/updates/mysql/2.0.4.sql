ALTER TABLE `#__bigbluebutton_meeting` ADD `catid` INT(7) NOT NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `#__bigbluebutton_event` ADD `catid` INT(7) NOT NULL DEFAULT 0 AFTER `event_title`;

