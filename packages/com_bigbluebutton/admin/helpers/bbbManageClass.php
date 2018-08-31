<?php
/**
 * @package    BigBlueButton
 *
 * @created    19th July, 2018
 * @author     Jibon L. Costa <jiboncosta57@gmail.com>
 * @website    https://www.hoicoimasti.com
 * @copyright  Copyright (C) Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


require_once JPATH_ROOT."/administrator/components/com_bigbluebutton/libs/bigbluebutton-api-php/vendor/autoload.php";

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

class BBBManageClass{
	
	private $params;
	private $item;
	
	public function __construct(){
		
		 $this->params = JComponentHelper::getParams('com_bigbluebutton');
		 $salt = trim($this->params->get('bbb_salt'));
		 $bbb_url = trim($this->params->get('bbb_url'));
		 
		 putenv("BBB_SECURITY_SALT=$salt");
		 putenv("BBB_SERVER_BASE_URL=$bbb_url");
		 		
	 }
	 
	 public function getMeeting($id = 1, $username = null, $password = null) {

		$isStudent = false;
		$gotoBBB = false;
		
		$output = array(
			'url' => null,
			'msg' => 'login error',
			'status' => false
		);
		
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "SELECT * FROM `#__bigbluebutton_meeting` WHERE `id`=" . $db->quote($id);
        $db->setQuery($query);
        $data = $db->loadObject();
		
		$this->item = $data;
		
		if(!$data){
			return $output;
		}
		
		$studentPass = $data->attendeepw;
		$teacherPass = $data->moderatorpw;
		
		if($password == $studentPass){
			$isStudent = true;
			$gotoBBB = true;
			
		}elseif($password == $teacherPass){
			
			$gotoBBB = true;
		}
		
		if($gotoBBB){
			$result = $this->prepareBBBUrl(md5($data->meetingid), $data->title, $studentPass, $teacherPass, $isStudent, $username); // I am using MD5 to make the id better
			if($result['status']){
				$output['url'] = $result['url'];
				$output['status'] = $result['status'];
				$output['msg'] = $result['msg'];
			}
		}
		

        return $output;
    }
	
	public function getRecordings($id){
		
		$output = array(
			'items' => null,
			'msg' => 'error',
			'status' => false
		);
		
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "SELECT meetingid FROM `#__bigbluebutton_meeting` WHERE `id`=" . $db->quote($id);
        $db->setQuery($query);
        $meetingId = $db->loadResult();
		
		$recordingParams = new GetRecordingsParameters();
		$recordingParams->setMeetingId(md5($meetingId));
		
		$bbb = new BigBlueButton();
		$response = $bbb->getRecordings($recordingParams);
		
		$config = new JConfig();
        $offset = $config->offset;
        date_default_timezone_set($offset);
		
		if ($response->getReturnCode() == 'SUCCESS') {
			
			$items = array();
			foreach ($response->getRawXml()->recordings->recording as $recording){
				
				if($recording->playback->format->url){
					$recording->startTime =  date('Y-m-d g:i A', round((float)$recording->startTime / 1000));
					$recording->endTime =  date('Y-m-d g:i A', round((float)$recording->endTime / 1000));
					array_push($items, $recording);
				}
				
			}
			$output['items'] = $items;
			$output['status'] = true;
			$output['msg'] = $response->getReturnCode();
		}
		
		return $output;
	}
	
	public function deleteRecording($recordingId){
		
		$bbb = new BigBlueButton();
		$deleteRecordingsParams= new DeleteRecordingsParameters($recordingId); // get from "Get Recordings"
		$response = $bbb->deleteRecordings($deleteRecordingsParams);
		
		$output = array(
			'msg' => 'error',
			'status' => false
		);

		if ($response->getReturnCode() == 'SUCCESS') {
			$output['msg'] = "success";
			$output['status'] = "true";
		} 
		
		return $output;
	}
	
	public function eventLogin($item, $password, $name){
		
		$output = array(
			'url' => null,
			'msg' => 'login error',
			'status' => false
		);
		
		$timeIsFor = $this->timeValidation($item);
		
		if($timeIsFor == "now"){
			if($item->event_password == 1){
				$output = $this->getMeeting($item->meeting_id, $name, $password);
				
			} else{
				
				if($item->custom_event_pass == $password){
					$output = $this->getMeeting($item->meeting_id, $name, $item->meeting_attendeepw);
					
				}else{
					$output = $this->getMeeting($item->meeting_id, $name, $password);
				}
			}
		}else{
			$output['msg'] = $timeIsFor;
		}
		
		
		return $output;
	}
	
	protected function timeValidation($item){
		
		if($item->timezone == 1){
			$config = JFactory::getConfig();
			$timeZone = $config->get('offset');
		} else{
			$timeZone = $item->event_timezone;
		}
		
		date_default_timezone_set($timeZone);

        $current_time = time();
        $start = new DateTime($item->event_start);
        $end = new DateTime($item->event_end);

        if ($start->getTimestamp() > $current_time) {
            return 'future';
			
        } elseif ($start->getTimestamp() < $current_time && $end->getTimestamp() > $current_time) {
            return 'now';
			
        } else {
            return 'past';
        }
	}
	 
	private function prepareBBBUrl($meetingID, $meetingName, $studentPass, $teacherPass, $isStudent, $username){
		
		$bbb = new BigBlueButton();
		
		$output = array(
			'url' => null,
			'msg' => 'error',
			'status' => false
		);

		$createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
		$createMeetingParams->setAttendeePassword($studentPass);
		$createMeetingParams->setModeratorPassword($teacherPass);
		$createMeetingParams->setLogoutUrl(JURI::root());
		
		if($this->item->record == 1){
			$createMeetingParams->setRecord(true);
			$createMeetingParams->setAllowStartStopRecording(true);
			
			if($this->item->duration !== 0){
				$createMeetingParams->setDuration($this->item->duration);
			}
		}
		
		if($this->item->maxparticipants !== '-1'){
			$createMeetingParams->setMaxParticipants($this->item->maxparticipants);
		}
		
		
		if($this->item->branding == 2){
			$createMeetingParams->setCopyright($this->params->get('copyright'));
			$createMeetingParams->setLogo(JURI::root().$this->params->get('logo'));
			
		} elseif($this->item->branding == 1){
			
			$createMeetingParams->setCopyright($this->item->copyright);
			$createMeetingParams->setLogo(JURI::root().$this->item->logo);
		}
		
		$response = $bbb->createMeeting($createMeetingParams);
		
		if ($response->getReturnCode() == 'FAILED') {
			$output['msg'] =  'Can\'t create room! please contact our administrator.';
		} else{
			if ($isStudent) {
				
				$joinMeetingParams = new JoinMeetingParameters($meetingID, $username, $studentPass); // $moderator_password for moderator
				$joinMeetingParams->setRedirect(true);
				if($this->item->enable_htmlfive == 2 || $this->item->enable_htmlfive == 3){
					$joinMeetingParams->setJoinViaHtml5(true);
				}
				$url = $bbb->getJoinMeetingURL($joinMeetingParams);
				
				if($url){
					$output['url'] = $url;
					$output['status'] = true;
					$output['msg'] = 'success';
				}
			}else{
				
				$joinMeetingParams = new JoinMeetingParameters($meetingID, $username, $teacherPass); // $moderator_password for moderator
				$joinMeetingParams->setRedirect(true);
				if($this->item->enable_htmlfive == 1 || $this->item->enable_htmlfive == 3){
					$joinMeetingParams->setJoinViaHtml5(true);
				}
				$url = $bbb->getJoinMeetingURL($joinMeetingParams);
				
				if($url){
					$output['url'] = $url;
					$output['status'] = true;
					$output['msg'] = 'success';
				} 
			}
		}
		
		return $output;
		
	}
	
	public function formatTimeZoneWithGMT($event){
		$timeZone = "";
		$config = JFactory::getConfig();
		
		if($event->timezone == 1){
			$timeZone = $config->get('offset');
		} else{
			$timeZone = $event->event_timezone;
		}
		$dtz = new DateTimeZone($timeZone);
		$time_in_sofia = new DateTime('now', $dtz);
		$offset = $dtz->getOffset($time_in_sofia) / 3600;

		$GMT = $timeZone." (GMT" . ($offset < 0 ? $offset : "+" . $offset).")";
		return $GMT;
	}
	
	public function sendInvitationEmails($event, $redirect = true){
		
		$config = JFactory::getConfig();
		$host =  JUri::getInstance()->getHost();
		$mailer = JFactory::getMailer();
		$app = JFactory::getApplication();
		
		if($event->timezone == 1){
			$timeZone = $config->get('offset');
		} else{
			$timeZone = $event->event_timezone;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = "SELECT * FROM `#__bigbluebutton_meeting` WHERE `id`=" . $db->quote($event->meeting_id);
		$db->setQuery($query);
		$meeting = $db->loadObject();
		
		$mettingRoomName = $meeting->title;
		$mettingRoomAttendeepw = $meeting->attendeepw;
		$timeZoneWithGMT = $this->formatTimeZoneWithGMT($event);
		$attendeeEmailAddresses = explode(",", rtrim($event->emails, ","));
		$user = JFactory::getUser($event->created_by);
		$invitationSender = $user->name;
		$eventDes = $event->event_des;
		
		if($event->event_password == 0){
			$mettingRoomAttendeepw = $event->custom_event_pass;
		}
		if($meeting->assign_to){
			if($user = JFactory::getUser($meeting->assign_to)){
				$invitationSender = $user->name;
			}
		}
		if($event->event_des){
			$eventDes = strip_tags($event->event_des);
		}
		
		$subject = JText::sprintf('COM_BIGBLUEBUTTON_ATTENDEE_INVITATION_EMAIL_SUBJECT', $invitationSender, $event->event_start, $event->event_end);
		$body = JText::sprintf('COM_BIGBLUEBUTTON_ATTENDEE_INVITATION_EMAIL_BODY', $invitationSender, $invitationSender, $mettingRoomName, $event->event_title, 
		$event->event_des, $event->event_start, $event->event_end, $timeZoneWithGMT, $mettingRoomAttendeepw, $event->join_url, $event->join_url, $config->get('sitename'));
		
		$icsFile = $this->createICSfile($user, $host, $event->id, $timeZone, $event->event_start, $event->event_end, $event->event_title, 
		$eventDes, $mettingRoomName, $event->join_url);

		$sender = array(
		    $config->get('mailfrom'),
		    $config->get('fromname')
		);
		
		$mailer->setSender($sender);
       		 $mailer->addRecipient($attendeeEmailAddresses);
		
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		$mailer->isHTML(true);
		$mailer->addAttachment($icsFile);
		$send = $mailer->Send();
				
		if ($send == "true") {
            		unlink($icsFile);
			if($redirect){
				$app->redirect( Juri::current().'?option=com_bigbluebutton&view=event&layout=edit&id='.$event->id, JText::_('COM_BIGBLUEBUTTON_ATTENDEE_INVITATION_EMAIL_SUCCESS'), 'success');
			}
		} else {
		    unlink($icsFile);
				if($redirect){
					$app->redirect( Juri::current().'?option=com_bigbluebutton&view=event&layout=edit&id='.$event->id, JText::_('COM_BIGBLUEBUTTON_ATTENDEE_INVITATION_EMAIL_FAILURE')." ".$send, 'error');
				}
		}
	}
	
	protected function createICSfile($user, $host, $eventID, $userTimeZone, $start, $end, $title, $description, $roomName, $url) {

		date_default_timezone_set('UTC');
		$current = date("Ymd\THis\Z", time());   // TZOFFSETTO of "right now"

		date_default_timezone_set($userTimeZone);

		$ics = "BEGIN:VCALENDAR\n";
		$ics .= "PRODID:-//{$host}//Event Management\n";
		$ics .= "VERSION:2.0\n";
		$ics .= "METHOD:PUBLISH\n";
		$ics .= "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";
		$ics .= "BEGIN:VTIMEZONE\n";
		$ics .= "TZID:{$userTimeZone}\n";
		$ics .= "TZURL:http://tzurl.org/zoneinfo-outlook/{$userTimeZone}\n";
		$ics .= "X-LIC-LOCATION:{$userTimeZone}\n";
		$ics .= "BEGIN:STANDARD\n";
		$ics .= "TZOFFSETFROM:" . date('O', time()) . "\n";
		$ics .= "END:STANDARD\n";
		$ics .= "END:VTIMEZONE\n";
		$ics .= "BEGIN:VEVENT\n";
		$ics .= "CLASS:PUBLIC\n";
		$ics .= "ORGANIZER;CN={$user->name}:mailto:{$user->email}\n";
		$ics .= "DTSTAMP:" . $current . "\n";
		$ics .= "DTSTART;TZID={$userTimeZone}:" . date("Ymd\THis", strtotime($start)) . "\n";
		$ics .= "DTEND;TZID={$userTimeZone}:" . date("Ymd\THis", strtotime($end)) . "\n";
		$ics .= "LOCATION:{$roomName}\n";
		$ics .= "PRIORITY:5\n";
		$ics .= "SEQUENCE:0\n";
		$ics .= "SUMMARY;LANGUAGE=en-us:{$title}\n";
		$ics .= "DESCRIPTION:{$description}\n";
		$ics .= "TRANSP:OPAQUE\n";
		$ics .= "UID:" . uniqid('Myna_') . "\n";
		$ics .= "X-MICROSOFT-CDO-BUSYSTATUS:BUSY\n";
		$ics .= "X-MICROSOFT-CDO-IMPORTANCE:1\n";
		$ics .= "X-MICROSOFT-DISALLOW-COUNTER:FALSE\n";
		$ics .= "X-MS-OLK-ALLOWEXTERNCHECK:TRUE\n";
		$ics .= "X-MS-OLK-AUTOFILLLOCATION:FALSE\n";
		$ics .= "X-MS-OLK-CONFTYPE:0\n";
		//Here is to set the reminder for the event.
		$ics .= "BEGIN:VALARM\n";
		$ics .= "DESCRIPTION:{$title}\n";
		$ics .= "TRIGGER:-PT10M\n";
		$ics .= "ACTION:DISPLAY\n";
		$ics .= "DESCRIPTION:Reminder\n";
		$ics .= "END:VALARM\n";
		$ics .= "END:VEVENT\n";
		$ics .= "END:VCALENDAR\n";

		$file = JPATH_ROOT . "/tmp/" . $eventID . "_calender.ics";

		file_put_contents($file, $ics);

		if (file_exists($file)) {
		    return $file;
		}
		return false;
    }
}

?>
