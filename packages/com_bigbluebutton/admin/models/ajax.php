<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2018 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Bigbluebutton Ajax Model
 */
class BigbluebuttonModelAjax extends JModelList
{
	protected $app_params;
	
	public function __construct() 
	{		
		parent::__construct();		
		// get params
		$this->app_params	= JComponentHelper::getParams('com_bigbluebutton');
		
	}

	// Used in recording
	public function getRecording($meetingId){
		require_once JPATH_COMPONENT_ADMINISTRATOR."/helpers/bbbManageClass.php";
		$class = new BBBManageClass();
		$result = $class->getRecordings($meetingId);
		return $result;
	}

	public function deleteRecording($recordingId){
		require_once JPATH_COMPONENT_ADMINISTRATOR."/helpers/bbbManageClass.php";
		$class = new BBBManageClass();
		$result = $class->deleteRecording($recordingId);
		return $result;
	}

}
