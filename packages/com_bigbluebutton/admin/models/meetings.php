 <?php
/**
* @version		$Id:default.php 1 2015-03-05 16:31:34Z Jibon $
* @package		Bigbluebutton
* @subpackage 	Models
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bigbluebutton/tables');

class BigbluebuttonModelmeetings extends JModelList
{
	public function __construct($config = array())
	{
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                            'meetingName', 'a.meetingName',
                            'moderatorPW', 'a.moderatorPW',
                            'attendeePW', 'a.attendeePW',
                            'waitForModerator', 'a.waitForModerator',
                            'id', 'a.id',
                        );
        
        }

		parent::__construct($config);	

	}
	
	protected function populateState($ordering = null, $direction = null)
	{
			parent::populateState();
			$app = JFactory::getApplication();
           		$config = JFactory::getConfig();
			$id = $app->input->getInt('id', null);
			$this->setState('meetinglist.id', $id);			
			
			// Load the filter state.
			$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
			$this->setState('filter.search', $search);

			$app = JFactory::getApplication();
			$value = $app->getUserStateFromRequest('global.list.limit', 'limit', $config->get('list_limit'));
			$limit = $value;
			$this->setState('list.limit', $limit);
			
			$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
			$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
			$this->setState('list.start', $limitstart);
			
			$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
			$this->setState('list.ordering', $value);			
						$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
			$this->setState('list.direction', $value);

					
	}
    		
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('meetinglist.id');
						return parent::getStoreId($id);
	}	
	
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return	object	A JDatabaseQuery object to retrieve the data set.
	 */
	protected function getListQuery()
	{
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);		
		$query->select('a.*');
		$query->from('#__bbb_meetings as a');
	
		 				// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.meetingName LIKE ' . $search . '  OR a.moderatorPW LIKE ' . $search . '  OR a.attendeePW LIKE ' . $search . '  OR a.waitForModerator LIKE ' . $search . ' )');
			}
		}
				
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'meetingId');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		if(empty($orderCol)) $orderCol = 'meetingId';
		if(empty($orderDirn)) $orderDirn = 'ASC'; 		
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
							
		return $query;
	}	
}
