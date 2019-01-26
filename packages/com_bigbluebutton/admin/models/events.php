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
 * Events Model
 */
class BigbluebuttonModelEvents extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.event_title','event_title',
				'c.title','category_title',
				'c.id', 'category_id',
				'a.catid', 'catid',
				'a.event_start','event_start',
				'a.event_end','event_end',
				'a.meeting_id','meeting_id'
			);
		}

		parent::__construct($config);
	}

	function reSendEmail(){

	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$event_title = $this->getUserStateFromRequest($this->context . '.filter.event_title', 'filter_event_title');
		$this->setState('filter.event_title', $event_title);

		$category = $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$catid = $app->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid');
		$this->setState('filter.catid', $catid);

		$event_start = $this->getUserStateFromRequest($this->context . '.filter.event_start', 'filter_event_start');
		$this->setState('filter.event_start', $event_start);

		$event_end = $this->getUserStateFromRequest($this->context . '.filter.event_end', 'filter_event_end');
		$this->setState('filter.event_end', $event_end);

		$meeting_id = $this->getUserStateFromRequest($this->context . '.filter.meeting_id', 'filter_meeting_id');
		$this->setState('filter.meeting_id', $meeting_id);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// load parent items
		$items = parent::getItems();

		// set values to display correctly.
		if (BigbluebuttonHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				$access = (JFactory::getUser()->authorise('event.access', 'com_bigbluebutton.event.' . (int) $item->id) && JFactory::getUser()->authorise('event.access', 'com_bigbluebutton'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}
		foreach($items as &$item){
			$item->join_url = "<a href='".JURI::root()."index.php?option=com_bigbluebutton&view=eventview&id=".$item->id."' target='_blank'> ". JText::_("COM_BIGBLUEBUTTON_GET_LINK")."</a>";
		}
        
		// return items
		return $items;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');
		$query->select($db->quoteName('c.title','category_title'));

		// From the bigbluebutton_item table
		$query->from($db->quoteName('#__bigbluebutton_event', 'a'));
		$query->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('c.id') . ')');

		// From the bigbluebutton_meeting table.
		$query->select($db->quoteName('g.title','meeting_id_title'));
		$query->join('LEFT', $db->quoteName('#__bigbluebutton_meeting', 'g') . ' ON (' . $db->quoteName('a.meeting_id') . ' = ' . $db->quoteName('g.id') . ')');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// Implement View Level Access
		if (!$user->authorise('core.options', 'com_bigbluebutton'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.event_title LIKE '.$search.' OR a.catid LIKE '.$search.' OR a.meeting_id LIKE '.$search.' OR g.title LIKE '.$search.')');
			}
		}


		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.category IN (' . $categoryId . ')');
		}


		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get list export data.
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getExportData($pks)
	{
		// setup the query
		if (BigbluebuttonHelper::checkArray($pks))
		{
			// Set a value to know this is exporting method.
			$_export = true;
			// Get the user object.
			$user = JFactory::getUser();
			// Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Select some fields
			$query->select('a.*');

			// From the bigbluebutton_event table
			$query->from($db->quoteName('#__bigbluebutton_event', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');
			// Implement View Level Access
			if (!$user->authorise('core.options', 'com_bigbluebutton'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// Order the results by ordering
			$query->order('a.ordering  ASC');

			// Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// set values to display correctly.
				if (BigbluebuttonHelper::checkArray($items))
				{
					foreach ($items as $nr => &$item)
					{
						$access = (JFactory::getUser()->authorise('event.access', 'com_bigbluebutton.event.' . (int) $item->id) && JFactory::getUser()->authorise('event.access', 'com_bigbluebutton'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						// unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (BigbluebuttonHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}

				foreach($items as &$item){
			$item->join_url = "<a href='".JURI::root()."index.php?option=com_bigbluebutton&view=eventview&id=".$item->id."' target='_blank'> ". JText::_("COM_BIGBLUEBUTTON_GET_LINK")."</a>";
		}
				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// get the columns
		$columns = $db->getTableColumns("#__bigbluebutton_event");
		if (BigbluebuttonHelper::checkArray($columns))
		{
			// remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.event_title');
		$id .= ':' . $this->getState('filter.category');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.catid');
		$id .= ':' . $this->getState('filter.event_start');
		$id .= ':' . $this->getState('filter.event_end');
		$id .= ':' . $this->getState('filter.meeting_id');

		return parent::getStoreId($id);
	}
}
