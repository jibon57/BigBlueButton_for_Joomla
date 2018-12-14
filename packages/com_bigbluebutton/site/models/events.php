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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Bigbluebutton Model for Events
 */
class BigbluebuttonModelEvents extends JModelList
{
	/**
	 * Model user data.
	 *
	 * @var        strings
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
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true; 
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__bigbluebutton_event as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.meeting_id','a.event_title','a.alias','a.event_des','a.event_start','a.event_end','a.timezone','a.event_timezone','a.event_password','a.custom_event_pass','a.join_url','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','meeting_id','event_title','alias','event_des','event_start','event_end','timezone','event_timezone','event_password','custom_event_pass','join_url','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__bigbluebutton_event', 'a'));
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
				// Access check.
				$access = ($user->authorise('event.access', 'com_bigbluebutton.event.' . (int) $item->id) &&  $user->authorise('event.access', 'com_bigbluebutton'));
				if (!$access)
				{
				    unset($items[$nr]);
				    continue;
				} else {
				    // Always create a slug for sef URL's
				    $item->slug = (isset($item->alias) && isset($item->id)) ? $item->id . ':' . $item->alias : $item->id;
				    // Make sure the content prepare plugins fire on event_des
				    $_event_des = new stdClass();
				    $_event_des->text =& $item->event_des; // value must be in text
				    // Since all values are now in text (Joomla Limitation), we also add the field name (event_des) to context
				    $this->_dispatcher->trigger("onContentPrepare", array('com_bigbluebutton.events.event_des', &$_event_des, &$this->params, 0));
				    // set meeting_idIdMeetingB to the $item object.
				    $item->meeting_idIdMeetingB = $this->getMeeting_idIdMeetingAbca_B($item->meeting_id);
				}
			}
		} 

		// return items
		return $items;
	} 

	/**
	 * Method to get an array of Meeting Objects.
	 *
	 * @return mixed  An array of Meeting Objects on success, false on failure.
	 *
	 */
	public function getMeeting_idIdMeetingAbca_B($meeting_id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__bigbluebutton_meeting as b
		$query->select($db->quoteName(
			array('b.id','b.title'),
			array('meeting_id','meeting_title')));
		$query->from($db->quoteName('#__bigbluebutton_meeting', 'b'));
		$query->where('b.id = ' . $db->quote($meeting_id));

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}
  
}
