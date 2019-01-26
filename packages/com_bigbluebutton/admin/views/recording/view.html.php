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
 * Bigbluebutton View class for the Recording
 */
class BigbluebuttonViewRecording extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get component params
		$this->params = JComponentHelper::getParams('com_bigbluebutton');
		// get the application
		$this->app = JFactory::getApplication();
		// get the user object
		$this->user	= JFactory::getUser();
		// get global action permissions
		$this->canDo = BigbluebuttonHelper::getActions('recording');
		// Initialise variables.
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// add the tool bar
			$this->addToolBar();
		}

		// set the document
		$this->setDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function setDocument()
	{

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new bigbluebuttonHeaderCheck;
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/administrator/components/com_bigbluebutton/assets/css/recording.css', (BigbluebuttonHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// hide the main menu
		$this->app->input->set('hidemainmenu', true);
		// add title to the page
		JToolbarHelper::title(JText::_('COM_BIGBLUEBUTTON_RECORDING'),'joomla');
		// add the back button
		// JToolBarHelper::custom('recording.back', 'undo-2', '', 'COM_BIGBLUEBUTTON_BACK', false);
		// add cpanel button
		JToolBarHelper::custom('recording.dashboard', 'grid-2', '', 'COM_BIGBLUEBUTTON_DASH', false);

		// set help url for this view if found
		$help_url = BigbluebuttonHelper::getHelpUrl('recording');
		if (BigbluebuttonHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_BIGBLUEBUTTON_HELP_MANAGER', false, $help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_bigbluebutton');
		}
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		// use the helper htmlEscape method instead.
		return BigbluebuttonHelper::htmlEscape($var, $this->_charset);
	}
}
