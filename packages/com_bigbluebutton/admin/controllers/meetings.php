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

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Meetings Controller
 */
class BigbluebuttonControllerMeetings extends JControllerAdmin
{
	protected $text_prefix = 'COM_BIGBLUEBUTTON_MEETINGS';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Meeting', $prefix = 'BigbluebuttonModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('meeting.export', 'com_bigbluebutton') && $user->authorise('core.export', 'com_bigbluebutton'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			JArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Meetings');
			// get the data to export
			$data = $model->getExportData($pks);
			if (BigbluebuttonHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				BigbluebuttonHelper::xls($data,'Meetings_'.$date->format('jS_F_Y'),'Meetings exported ('.$date->format('jS F, Y').')','meetings');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_BIGBLUEBUTTON_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_bigbluebutton&view=meetings', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('meeting.import', 'com_bigbluebutton') && $user->authorise('core.import', 'com_bigbluebutton'))
		{
			// Get the import model
			$model = $this->getModel('Meetings');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (BigbluebuttonHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('meeting_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'meetings');
				$session->set('dataType_VDM_IMPORTINTO', 'meeting');
				// Redirect to import view.
				$message = JText::_('COM_BIGBLUEBUTTON_IMPORT_SELECT_FILE_FOR_MEETINGS');
				$this->setRedirect(JRoute::_('index.php?option=com_bigbluebutton&view=import', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_BIGBLUEBUTTON_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_bigbluebutton&view=meetings', false), $message, 'error');
		return;
	}  
}
