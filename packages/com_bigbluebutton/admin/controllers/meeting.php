<?php
/**
* @version		$Id:default.php 1 2015-03-05 16:31:34Z Jibon $
* @package		Bigbluebutton
* @subpackage 	Controllers
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
jimport('joomla.application.component.controllerform');

/**
 * BigbluebuttonMeeting Controller
 *
 * @package    Bigbluebutton
 * @subpackage Controllers
 */
class BigbluebuttonControllerMeeting extends JControllerForm
{
	public function __construct($config = array())
	{
	
		$this->view_item = 'meeting';
		$this->view_list = 'meetings';
		parent::__construct($config);
	}	
	
	/**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the PHP class name.
	 *
	 * @return  JModel
	 * @since   1.6
	 */
	public function getModel($name = 'Meeting', $prefix = 'BigbluebuttonModel', $config = array('ignore_request' => false))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}	
}// class
?>