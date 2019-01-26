<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2019 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Bigbluebutton Import View
 */
class BigbluebuttonViewImport extends JViewLegacy
{
	protected $headerList;
	protected $hasPackage = false;
	protected $headers;
	protected $hasHeader = 0;
	protected $dataType;

	public function display($tpl = null)
	{		
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			BigbluebuttonHelper::addSubmenu('import');
		}

		$paths = new stdClass;
		$paths->first = '';
		$state = $this->get('state');

		$this->paths = &$paths;
		$this->state = &$state;
                // get global action permissions
		$this->canDo = BigbluebuttonHelper::getActions('import');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		// get the session object
		$session = JFactory::getSession();
		// check if it has package
		$this->hasPackage 	= $session->get('hasPackage', false);
		$this->dataType 	= $session->get('dataType', false);
		if($this->hasPackage && $this->dataType)
		{
			$this->headerList 	= json_decode($session->get($this->dataType.'_VDM_IMPORTHEADERS', false),true);
			$this->headers 		= BigbluebuttonHelper::getFileHeaders($this->dataType);
			// clear the data type
			$session->clear('dataType');
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_BIGBLUEBUTTON_IMPORT_TITLE'), 'upload');
		JHtmlSidebar::setAction('index.php?option=com_bigbluebutton&view=import');

		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_bigbluebutton');
		}

		// set help url for this view if found
		$help_url = BigbluebuttonHelper::getHelpUrl('import');
		if (BigbluebuttonHelper::checkString($help_url))
		{
			   JToolbarHelper::help('COM_BIGBLUEBUTTON_HELP_MANAGER', false, $help_url);
		}
	}
}
