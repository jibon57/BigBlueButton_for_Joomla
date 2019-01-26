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
 * Bigbluebutton Model for Recording
 */
class BigbluebuttonModelRecording extends JModelList
{
	/**
	 * Model user data.
	 *
	 * @var  strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$this->user = JFactory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true; 
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__bigbluebutton_meeting as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.meetingid','a.title','a.catid','a.alias','a.description','a.moderatorpw','a.attendeepw','a.maxparticipants','a.record','a.duration','a.enable_htmlfive','a.branding','a.copyright','a.logo','a.assign_to','a.join_url','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','meetingid','title','catid','alias','description','moderatorpw','attendeepw','maxparticipants','record','duration','enable_htmlfive','branding','copyright','logo','assign_to','join_url','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__bigbluebutton_meeting', 'a'));
		// Get where a.published is 1
		$query->where('a.published = 1');

		// return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$user = JFactory::getUser();
		// check if this user has permission to access items
		if (!$user->authorise('recording.access', 'com_bigbluebutton'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('Not authorised!'), 'error');
			// redirect away if not a correct (TODO for now we go to default view)
			$app->redirect('index.php?option=com_bigbluebutton');
			return false;
		}
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_bigbluebutton', true);

		// Insure all item fields are adapted where needed.
		if (BigbluebuttonHelper::checkArray($items))
		{
			// Load the JEvent Dispatcher
			JPluginHelper::importPlugin('content');
			$this->_dispatcher = JEventDispatcher::getInstance();
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
				// Check if item has params, or pass whole item.
				$params = (isset($item->params) && BigbluebuttonHelper::checkJson($item->params)) ? json_decode($item->params) : $item;
				// Make sure the content prepare plugins fire on description
				$_description = new stdClass();
				$_description->text =& $item->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_bigbluebutton.recording.description', &$_description, &$params, 0));
			}
		}

		// return items
		return $items;
	}
}
