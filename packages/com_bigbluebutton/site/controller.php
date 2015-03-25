<?php
/**
* @version		$Id:controller.php  1 2015-03-06 18:57:25Z Jibon $
* @package		Bigbluebutton
* @subpackage 	Controllers
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR."/helpers/bigbluebutton.php";
/**
 * Bigbluebutton Controller
 *
 * @package    
 * @subpackage Controllers
 */
class BigbluebuttonController extends JControllerLegacy
{

    /**
    * Constructor.
    *
    * @param	array An optional associative array of configuration settings.
    * @see		JController
    */
    	public function display($cachable = false, $urlparams = false)
	{
			 
		return parent::display();
	}
	
	public function getMeeting($id = 1, $username = null, $password = null){
		
		$input = JFactory::getApplication()->input;  	
		$id = $input->get('meetingID');
		$username = $input->get('username');
		$password = $input->get('password');
		
		$bbb = new BigbluebuttonHelper();
		$get = $bbb->meeting($id, $username, $password);
		$final = array();
		if (preg_match("/meetingID/",$get)) {
			$data['status'] = "yes";
			$data['url']= $get;
			array_push($final, $data);
		}
		else {
			$data['status'] = "no";
			$data['message']= $get;
			array_push($final, $data);
		}
		header('Content-Type: application/json');
		echo json_encode($final);
		jexit();
	}	
	

}// class
?>
