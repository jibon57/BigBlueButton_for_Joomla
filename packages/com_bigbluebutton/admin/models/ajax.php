<?php
/**
 * @package    BigBlueButton
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <jiboncosta57@gmail.com>
 * @website    https://www.hoicoimasti.com
 * @copyright  Copyright (C) 2018 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

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
