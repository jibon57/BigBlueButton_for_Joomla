ALTER TABLE `#__bigbluebutton_event` ADD `emails` TEXT NOT NULL AFTER `custom_event_pass`;

ALTER TABLE `#__bigbluebutton_event` ADD `send_invitation_email` INT(1) NOT NULL DEFAULT 0 AFTER `meeting_id`;
