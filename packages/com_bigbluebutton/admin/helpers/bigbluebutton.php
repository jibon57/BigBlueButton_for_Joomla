<?php
/**
 * @version		$Id: #component#.php 170 2013-11-12 22:44:37Z michel $
 * @package		Joomla.Framework
 * @subpackage		HTML
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once __DIR__ . '/bbb-api.php';


class BigbluebuttonHelper
{
    
      
    protected $salt;
    protected $url;
    protected $dialNumber;
    
    function __construct () {
  	$params = JComponentHelper::getParams('com_bigbluebutton');
        $this->salt = $params->get('salt');
    	$this->url = $params->get('url');
    	$this->dialNumber = $params->get('dialNumber');
    }
    
    public function meeting($id = 1, $username = null, $password = null) {
    	    	   	
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query = "SELECT * FROM `#__bbb_meetings` WHERE `id`=".$id;
    	$db->setQuery($query);
	$data = $db->loadObject();
	
	if ($data->attendeePW == $password || $data->moderatorPW == $password) {
		
		$creationParams = array(
			'meetingId' => $id,
			'meetingName' => $data->meetingName,
			'attendeePw' => $data->attendeePW,
			'moderatorPw' => $data->moderatorPW,
			'logoutUrl' => JUri::base(),
			'welcomeMsg' => 'Welcome to '.$data->meetingName,
			'dialNumber' => $this->dialNumber,
			'voiceBridge' => $data->voiceBridge,
			'maxParticipants' => $data->maxParticipants,
			'record' => $data->record,
			'duration' => $data->duration
		);
		$itsAllGood = true;
		$bbb = new BigBlueButton($this->salt, $this->url);

		
		try {$result = $bbb->createMeetingWithXmlResponseArray($creationParams);}
			catch (Exception $e) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
				$itsAllGood = false;
		}
	
		if ($itsAllGood == true) {
			if ($result == null) {
				$output = "Failed to get any response. Maybe we can't contact the BBB server.";

			}	
			else { 
				if ($result['returncode'] == 'SUCCESS') {
					$output = $this->getlink ($id, $username, $password);
				}
				else {
					$output = "Meeting creation failed";
				}
			}
		}
	}
	
	else {
		$output = "Sorry password don't match";

	}   		    
    	return $output;	
    }
    
    protected function getlink ($id = null, $username= null, $password= null) {
    	$joinParams = array(
			'meetingId' => $id, 			
			'username' => $username,	
			'password' => $password,
			'logoutUrl' => JUri::base()				
		);
				
		$bbb = new BigBlueButton($this->salt, $this->url);
		$itsAllGood = true;
		try {$result = $bbb->getJoinMeetingURL($joinParams);}
			catch (Exception $e) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
				$itsAllGood = false;
			}

		if ($itsAllGood == true) {
			return $result;
		}
    }
    
    public function isMeetingRunning ($meetingId = 1) {
    	
    	$bbb = new BigBlueButton($this->salt, $this->url);
    	$itsAllGood = true;
	try {$result = $bbb->isMeetingRunningWithXmlResponseArray($meetingId);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		return $result['running'];
	}
   }
  
    public function endMeeting ($meetingId = null, $password= null) {
  	
  	$bbb = new BigBlueButton($this->salt, $this->url);
  	$endParams = array(
		'meetingId' => $meetingId, 			
		'password' => $password,		
	);
	$itsAllGood = true;
	
	try {$result = $bbb->endMeetingWithXmlResponseArray($endParams);}
	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

	if ($itsAllGood == true) {

		if ($result == null) {
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}	
		else { 
			if ($result['returncode'] == 'SUCCESS') {
				echo "<p>Meeting succesfullly ended.</p>";
			}
			else {
				echo "<p>Failed to end meeting.</p>";
			}
		}
	}
    }
    
    public function getRecordings ($meetingId = 1) {
    	
    	$bbb = new BigBlueButton($this->salt, $this->url);
    	$recordingsParams = array(
		'meetingId' => $meetingId, 			
	);
	
	$itsAllGood = true;
	
	try {$result = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

	if ($itsAllGood == true) {
		if ($result == null) {
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}	
		else { 
			$final = array();
			if ($result['returncode'] == 'SUCCESS') {
				foreach ((array) $result as $data) {
					$item['recordId'] = (string) $data['recordId'][0];
					$item['playbackFormatUrl']= (string) $data['playbackFormatUrl'][0];					
					array_push($final, $item);
			}
				
			}
			else {
				echo "<p>Failed to get meeting info.</p>";
			}
		}
	}	
	echo json_encode($final);
	
    }
    
    public function publishRecordings($recordId = null) {
    	
    	$bbb = new BigBlueButton($this->salt, $this->url);
    	$recordingParams = array(
    		'recordId' => $recordId,
    		'publish' => 'true'
    	);
    	
    	$itsAllGood = true;
	try {$result = $bbb->publishRecordingsWithXmlResponseArray($recordingParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		echo $result['published'];
	}
    }
    
    public function deleteRecordings($recordId = null) {
    	
    	$bbb = new BigBlueButton($this->salt, $this->url);
    	$recordingParams = array(
    		'recordId' => $recordId,
    	);
    	$itsAllGood = true;
	try {$result = $bbb->deleteRecordingsWithXmlResponseArray($recordingParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		print_r($result);
	}
    }
      
}

class buildSubMenu
{
	
	/*
	 * Submenu for Joomla 3.x
	 */
	public static function addSubmenu($vName = 'meetings')
	{
        	if(version_compare(JVERSION,'3','<')){
		JSubMenuHelper::addEntry(
			JText::_('Meetings'),
			'index.php?option=com_bigbluebutton&view=meetings',
			($vName == 'meetings')
		);	
	} else {
		JHtmlSidebar::addEntry(
			JText::_('Meetings'),
			'index.php?option=com_bigbluebutton&view=meetings',
			($vName == 'meetings')
		);	
	}
	
	if(version_compare(JVERSION,'3','<')){
		JSubMenuHelper::addEntry(
			JText::_('Recording'),
			'index.php?option=com_bigbluebutton&view=records',
			($vName == 'records')
		);	
	} else {
		JHtmlSidebar::addEntry(
			JText::_('Recording'),
			'index.php?option=com_bigbluebutton&view=records',
			($vName == 'records')
		);	
	}
	
	if(version_compare(JVERSION,'3','<')){
		JSubMenuHelper::addEntry(
			JText::_(''),
			'index.php?option=com_bigbluebutton&view=',
			($vName == '')
		);	
	} else {
		JHtmlSidebar::addEntry(
			JText::_(''),
			'index.php?option=com_bigbluebutton&view=',
			($vName == '')
		);	
	}

	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 *
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
	
		if (empty($categoryId))
		{
			$assetName = 'com_bigbluebutton';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_bigbluebutton.category.'.(int) $categoryId;
			$level = 'category';
		}
	
		$actions = JAccess::getActions('com_bigbluebutton', $level);
	
		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}
	
		return $result;
	}	
	/**
	 * 
	 * Get the Extensions for Categories
	 */
	public static function getExtensions() 
	{
						
		static $extensions;
		
		if(!empty($extensions )) return $extensions;
		
		jimport('joomla.utilities.xmlelement');
		
		$xml = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/com_bigbluebutton/elements/extensions.xml', 'JXMLElement');		        
		$elements = $xml->xpath('extensions');
		$extensions = $xml->extensions->xpath('descendant-or-self::extension');
		
		return $extensions;
	} 	

	
	/**
	 *
	 * Returns views that associated with categories
	 */
	public static function getCategoryViews()
	{
	
		$extensions = self::getExtensions();
		$views = array();
		foreach($extensions as $extension ) {
			$views[$extension->listview->__toString()] = 'com_bigbluebutton.'.$extension->name->__toString();
		}
		return $views;
	}	
}

/**
 * Utility class for categories
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
abstract class JHtmlBigbluebutton
{
	/**
	 * @var	array	Cached array of the category items.
	 */
	protected static $items = array();
	
	/**
	 * Returns the options for extensions list
	 * 
	 * @param string $ext - the extension
	 */
	public static function extensions($ext) 
	{
		$extensions = BigbluebuttonHelper::getExtensions();
		$options = array();
		
		foreach ($extensions as $extension) {   
		
			$option = new stdClass();
			$option->text = JText::_(ucfirst($extension->name));
			$option->value = 'com_bigbluebutton.'.$extension->name;
			$options[] = $option;			
		}		
		return JHtml::_('select.options', $options, 'value', 'text', $ext, true);
	}
	
	/**
	 * Returns an array of categories for the given extension.
	 *
	 * @param	string	The extension option.
	 * @param	array	An array of configuration options. By default, only published and unpulbished categories are returned.
	 *
	 * @return	array
	 */
	public static function categories($extension, $cat_id,$name="categories",$title="Select Category", $config = array('attributes'=>'class="inputbox"','filter.published' => array(0,1)))
	{

			$config	= (array) $config;
			$db		= JFactory::getDbo();

			$query = $db->getQuery(true);

			$query->select('a.id, a.title, a.level');
			$query->from('#__bigbluebutton_categories AS a');
			$query->where('a.parent_id > 0');

			// Filter on extension.
			if($extension)
			    $query->where('extension = '.$db->quote($extension));
			
			$attributes = "";
			
			if (isset($config['attributes'])) {
				$attributes = $config['attributes'];
			}
			
			// Filter on the published state
			if (isset($config['filter.published'])) {
				
				if (is_numeric($config['filter.published'])) {
					
					$query->where('a.published = '.(int) $config['filter.published']);
					
				} else if (is_array($config['filter.published'])) {
					
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN ('.implode(',', $config['filter.published']).')');
					
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();
			
			// Assemble the list options.
			self::$items = array();
			self::$items[] = JHtml::_('select.option', '', JText::_($title));
			foreach ($items as &$item) {
								
				$item->title = str_repeat('- ', $item->level - 1).$item->title;
				self::$items[] = JHtml::_('select.option', $item->id, $item->title);
			}

		return  JHtml::_('select.genericlist', self::$items, $name, $attributes, 'value', 'text', $cat_id, $name);
		//return self::$items;
	}
}

