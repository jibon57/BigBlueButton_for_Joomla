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

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * Bigbluebutton Event Model
 */
class BigbluebuttonModelEvent extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_bigbluebutton.event';

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
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		// Get the itme main id
		$id = $this->input->getInt('id', null);
		$this->setState('event.id', $id);

		// Load the parameters.
		$params = $this->app->getParams();
		$this->setState('params', $params);
		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$this->user = JFactory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->initSet = true;

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('event.id');
		
		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// Get a db connection.
				$db = JFactory::getDbo();

				// Create a new query object.
				$query = $db->getQuery(true);

				// Get from #__bigbluebutton_event as a
				$query->select($db->quoteName(
			array('a.id','a.asset_id','a.meeting_id','a.event_title','a.alias','a.event_des','a.event_start','a.event_end','a.timezone','a.event_timezone','a.event_password','a.custom_event_pass','a.join_url','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','meeting_id','event_title','alias','event_des','event_start','event_end','timezone','event_timezone','event_password','custom_event_pass','join_url','published','created_by','modified_by','created','modified','version','hits','ordering')));
				$query->from($db->quoteName('#__bigbluebutton_event', 'a'));

				// Get from #__bigbluebutton_meeting as b
				$query->select($db->quoteName(
			array('b.id','b.asset_id','b.meetingid','b.title','b.alias','b.description','b.moderatorpw','b.attendeepw','b.maxparticipants','b.record','b.duration','b.enable_htmlfive','b.branding','b.copyright','b.logo','b.join_url','b.published','b.created_by','b.modified_by','b.created','b.modified','b.version','b.hits','b.ordering'),
			array('meeting_id','meeting_asset_id','meeting_meetingid','meeting_title','meeting_alias','meeting_description','meeting_moderatorpw','meeting_attendeepw','meeting_maxparticipants','meeting_record','meeting_duration','meeting_enable_htmlfive','meeting_branding','meeting_copyright','meeting_logo','meeting_join_url','meeting_published','meeting_created_by','meeting_modified_by','meeting_created','meeting_modified','meeting_version','meeting_hits','meeting_ordering')));
				$query->join('LEFT', ($db->quoteName('#__bigbluebutton_meeting', 'b')) . ' ON (' . $db->quoteName('a.meeting_id') . ' = ' . $db->quoteName('b.id') . ')');
				$query->where('a.id = ' . (int) $pk);
				// Get where a.published is 1
				$query->where('a.published = 1');

				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				// Load the results as a stdClass object.
				$data = $db->loadObject();

				if (empty($data))
				{
					$app = JFactory::getApplication();
					// If no data is found redirect to default page and show warning.
					$app->enqueueMessage(JText::_('COM_BIGBLUEBUTTON_NOT_FOUND_OR_ACCESS_DENIED'), 'warning');
					$app->redirect(JRoute::_('index.php?option=com_bigbluebutton&view=login'));
					return false;
				}
			// Load the JEvent Dispatcher
			JPluginHelper::importPlugin('content');
			$this->_dispatcher = JEventDispatcher::getInstance();
				// Make sure the content prepare plugins fire on event_des
				$_event_des = new stdClass();
				$_event_des->text =& $data->event_des; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (event_des) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_bigbluebutton.event.event_des', &$_event_des, &$this->params, 0));
				// Make sure the content prepare plugins fire on meeting_description
				$_meeting_description = new stdClass();
				$_meeting_description->text =& $data->meeting_description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (meeting_description) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_bigbluebutton.event.meeting_description', &$_meeting_description, &$this->params, 0));

				// set data object to item.
				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseWaring(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	} 
  
}
