CREATE TABLE IF NOT EXISTS `#__bbb_meetings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `meetingName` varchar(50) NOT NULL,
  `meetingVersion` int(5) NOT NULL DEFAULT '0',
  `moderatorPW` varchar(40) NOT NULL,
  `attendeePW` varchar(40) NOT NULL,
  `voiceBridge` int(11) NOT NULL DEFAULT '12345',
  `maxParticipants` int(5) NOT NULL DEFAULT '-1',
  `record` varchar(6) NOT NULL DEFAULT 'true',
  `duration` int(5) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

