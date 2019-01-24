<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2018 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Routing class from com_bigbluebutton
 *
 * @since  3.3
 */
class BigbluebuttonRouter extends JComponentRouterBase
{	
	/**
	 * Build the route for the com_bigbluebutton component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$params = JComponentHelper::getParams('com_bigbluebutton');
		
		if (empty($query['Itemid']))
		{
			$menuItem = $this->menu->getActive();
		}
		else
		{
			$menuItem = $this->menu->getItem($query['Itemid']);
		}

		$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

		if (isset($query['view']))
		{
			$view = $query['view'];

			if (empty($query['Itemid']))
			{
				$segments[] = $query['view'];
			}

			unset($query['view']);
		}
		
		// Are we dealing with a item that is attached to a menu item?
		if (isset($view) && ($mView == $view) and (isset($query['id'])) and ($mId == (int) $query['id']))
		{
			unset($query['view']);
			unset($query['catid']);
			unset($query['id']);
			return $segments;
		}

		if (isset($view) && isset($query['id']) && ($view === 'meeting' || $view === 'event' || $view === 'login' || $view === 'eventview' || $view === 'meetingview' || $view === 'meetings' || $view === 'events' || $view === 'calendar'))
		{
			if ($mId != (int) $query['id'] || $mView != $view)
			{
				if (($view === 'meeting' || $view === 'event' || $view === 'login' || $view === 'eventview' || $view === 'meetingview' || $view === 'meetings' || $view === 'events' || $view === 'calendar'))
				{
					$segments[] = $view;
					$id = explode(':', $query['id']);
					if (count($id) == 2)
					{
						$segments[] = $id[1];
					}
					else
					{
						$segments[] = $id[0];
					}
				}
			}
			unset($query['id']);
		}
		
		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments; 
		
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{		
		$count = count($segments);
		$vars = array();
		
		//Handle View and Identifier
		switch($segments[0])
		{
			case 'meeting':
				$vars['view'] = 'meeting';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				break;
			case 'event':
				$vars['view'] = 'event';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				break;
			case 'login':
				$vars['view'] = 'login';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('meeting', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'eventview':
				$vars['view'] = 'eventview';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('event', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'meetingview':
				$vars['view'] = 'meetingview';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('meeting', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'meetings':
				$vars['view'] = 'meetings';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('meeting', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'events':
				$vars['view'] = 'events';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('event', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'calendar':
				$vars['view'] = 'calendar';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('event', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
		}

		return $vars;
	} 

	protected function getVar($table, $where = null, $whereString = null, $what = null, $category = false, $operator = '=', $main = 'bigbluebutton')
	{
		if(!$where || !$what || !$whereString)
		{
			return false;
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array($what)));
		if ('categories' == $table || 'category' == $table || $category)
		{
			$getTable = '#__categories';
			$query->from($db->quoteName($getTable));
		}
		else
		{
			// we must check if the table exist (TODO not ideal)
			$tables = $db->getTableList();
			$app = JFactory::getApplication();
			$prefix = $app->get('dbprefix');
			$check = $prefix.$main.'_'.$table;
			if (in_array($check, $tables))
			{
				$getTable = '#__'.$main.'_'.$table;
				$query->from($db->quoteName($getTable));
			}
			else
			{
				return false;
			}
		}
		if (is_numeric($where))
		{
			return false;
		}
		elseif ($this->checkString($where))
		{
			// we must first check if this table has the column
			$columns = $db->getTableColumns($getTable);
			if (isset($columns[$whereString]))
			{
				$query->where($db->quoteName($whereString) . ' '.$operator.' '. $db->quote((string)$where));
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}
	
	protected function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}
}

function BigbluebuttonBuildRoute(&$query)
{
	$router = new BigbluebuttonRouter;
	
	return $router->build($query);
}

function BigbluebuttonParseRoute($segments)
{
	$router = new BigbluebuttonRouter;

	return $router->parse($segments);
}