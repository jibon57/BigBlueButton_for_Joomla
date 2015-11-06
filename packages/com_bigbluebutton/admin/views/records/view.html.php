<?php
/**
* @version		$Id:meeting.php 1 2015-03-05 16:31:34Z Jibon $
* @package		Bigbluebutton
* @subpackage 	Views
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.application.component.helper');
 
class BigbluebuttonViewrecords  extends JViewLegacy {


	protected $items;


	/**
	 *  Displays the list view
 	 * @param string $tpl   
     */
	public function display($tpl = null)
	{
		
		$this->items = $this->getlist();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		buildSubMenu::addSubmenu('records');		

		$this->addToolbar();
		if(!version_compare(JVERSION,'3','<')){
			$this->sidebar = JHtmlSidebar::render();
		}
		
		if(version_compare(JVERSION,'3','<')){
			$tpl = "25";
		}
		
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{	
		$canDo = buildSubMenu::getActions();
		$user = JFactory::getUser();
		JToolBarHelper::title( JText::_( 'Recordings' ), 'generic.png' );
		JToolBarHelper::preferences('com_bigbluebutton', '550');  
		if(!version_compare(JVERSION,'3','<')){		
			JHtmlSidebar::setAction('index.php?option=com_bigbluebutton&view=records');
		}
				
	}
	
	protected function getlist() {
		$db = JFactory::getDbo();
	    	$query = $db->getQuery(true);
	    	$query = "SELECT * FROM `#__bbb_meetings` WHERE `record` = 'true' ORDER BY `meetingId` ASC";
	    	$db->setQuery($query);
		$result = $db->loadAssocList();
		return $result;		
	}	
		
}
?>
