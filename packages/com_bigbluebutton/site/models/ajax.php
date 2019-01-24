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
		$this->app_params = JComponentHelper::getParams('com_bigbluebutton');

	}

	// Used in login
	function getLogin($meetingId, $password, $name){
		require_once JPATH_COMPONENT_ADMINISTRATOR."/helpers/bbbManageClass.php";
		$class = new BBBManageClass();
		$result = $class->getMeeting($meetingId, $name, $password);
		return $result;
	}

	// Used in eventview
	public function eventLogin($eventId, $password, $name){
		require_once JPATH_COMPONENT_ADMINISTRATOR."/helpers/bbbManageClass.php";
		require_once JPATH_COMPONENT_SITE."/models/event.php";
		
		$model = new BigbluebuttonModelEvent();
		$item = $model->getItem($eventId);
		
		$class = new BBBManageClass();
		$result = $class->eventLogin($item, $password, $name);
		
		return $result;
	}


	// Used in meetingview
function test(){
 echo "Test";
}
}
