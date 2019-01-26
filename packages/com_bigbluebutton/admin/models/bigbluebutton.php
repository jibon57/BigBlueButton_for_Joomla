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
 * Bigbluebutton Model
 */
class BigbluebuttonModelBigbluebutton extends JModelList
{
	public function getIcons()
	{
		// load user for access menus
		$user = JFactory::getUser();
		// reset icon array
		$icons  = array();
		// view groups array
		$viewGroups = array(
			'main' => array('png.meeting.add', 'png.meetings', 'png.meetings.catid', 'png.event.add', 'png.events', 'png.events.catid', 'png.recording')
		);
		// view access array
		$viewAccess = array(
			'recording.access' => 'recording.access',
			'recording.submenu' => 'recording.submenu',
			'recording.dashboard_list' => 'recording.dashboard_list',
			'meeting.create' => 'meeting.create',
			'meetings.access' => 'meeting.access',
			'meeting.access' => 'meeting.access',
			'meetings.submenu' => 'meeting.submenu',
			'meetings.dashboard_list' => 'meeting.dashboard_list',
			'meeting.dashboard_add' => 'meeting.dashboard_add',
			'event.create' => 'event.create',
			'events.access' => 'event.access',
			'event.access' => 'event.access',
			'events.submenu' => 'event.submenu',
			'events.dashboard_list' => 'event.dashboard_list',
			'event.dashboard_add' => 'event.dashboard_add');
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (BigbluebuttonHelper::checkArray($views))
			{
				foreach($views as $view)
				{
					$add = false;
					// external views (links)
					if (strpos($view,'||') !== false)
					{
						$dwd = explode('||', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $url) = $dwd;
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= $url;
							$image 		= $name.'.'.$type;
							$name 		= 'COM_BIGBLUEBUTTON_DASHBOARD_'.BigbluebuttonHelper::safeString($name,'U');
						}
					}
					// internal views
					elseif (strpos($view,'.') !== false)
					{
						$dwd = explode('.', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $action) = $dwd;
						}
						elseif (count($dwd) == 2)
						{
							list($type, $name) = $dwd;
							$action = false;
						}
						if ($action)
						{
							$viewName = $name;
							switch($action)
							{
								case 'add':
									$url 	= 'index.php?option=com_bigbluebutton&view='.$name.'&layout=edit';
									$image 	= $name.'_'.$action.'.'.$type;
									$alt 	= $name.'&nbsp;'.$action;
									$name	= 'COM_BIGBLUEBUTTON_DASHBOARD_'.BigbluebuttonHelper::safeString($name,'U').'_ADD';
									$add	= true;
								break;
								default:
									$url 	= 'index.php?option=com_categories&view=categories&extension=com_bigbluebutton.'.$name;
									$image 	= $name.'_'.$action.'.'.$type;
									$alt 	= $name.'&nbsp;'.$action;
									$name	= 'COM_BIGBLUEBUTTON_DASHBOARD_'.BigbluebuttonHelper::safeString($name,'U').'_'.BigbluebuttonHelper::safeString($action,'U');
								break;
							}
						}
						else
						{
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= 'index.php?option=com_bigbluebutton&view='.$name;
							$image 		= $name.'.'.$type;
							$name 		= 'COM_BIGBLUEBUTTON_DASHBOARD_'.BigbluebuttonHelper::safeString($name,'U');
							$hover		= false;
						}
					}
					else
					{
						$viewName 	= $view;
						$alt 		= $view;
						$url 		= 'index.php?option=com_bigbluebutton&view='.$view;
						$image 		= $view.'.png';
						$name 		= ucwords($view).'<br /><br />';
						$hover		= false;
					}
					// first make sure the view access is set
					if (BigbluebuttonHelper::checkArray($viewAccess))
					{
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
						$accessTo = '';
						$accessAdd = '';
						// acces checking start
						$accessCreate = (isset($viewAccess[$viewName.'.create'])) ? BigbluebuttonHelper::checkString($viewAccess[$viewName.'.create']):false;
						$accessAccess = (isset($viewAccess[$viewName.'.access'])) ? BigbluebuttonHelper::checkString($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? BigbluebuttonHelper::checkString($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? BigbluebuttonHelper::checkString($viewAccess[$viewName.'.dashboard_list']):false;
						// check for adding access
						if ($add && $accessCreate)
						{
							$accessAdd = $viewAccess[$viewName.'.create'];
						}
						elseif ($add)
						{
							$accessAdd = 'core.create';
						}
						// check if acces to view is set
						if ($accessAccess)
						{
							$accessTo = $viewAccess[$viewName.'.access'];
						}
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add	= $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_bigbluebutton');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_bigbluebutton');
						}
						if (BigbluebuttonHelper::checkString($accessAdd) && BigbluebuttonHelper::checkString($accessTo))
						{
							// check access
							if($user->authorise($accessAdd, 'com_bigbluebutton') && $user->authorise($accessTo, 'com_bigbluebutton') && $dashboard_add)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						elseif (BigbluebuttonHelper::checkString($accessTo))
						{
							// check access
							if($user->authorise($accessTo, 'com_bigbluebutton') && $dashboard_list)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						elseif (BigbluebuttonHelper::checkString($accessAdd))
						{
							// check access
							if($user->authorise($accessAdd, 'com_bigbluebutton') && $dashboard_add)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						else
						{
							$icons[$group][$i]			= new StdClass;
							$icons[$group][$i]->url 	= $url;
							$icons[$group][$i]->name 	= $name;
							$icons[$group][$i]->image 	= $image;
							$icons[$group][$i]->alt 	= $alt;
						}
					}
					else
					{
						$icons[$group][$i]			= new StdClass;
						$icons[$group][$i]->url 	= $url;
						$icons[$group][$i]->name 	= $name;
						$icons[$group][$i]->image 	= $image;
						$icons[$group][$i]->alt 	= $alt;
					}
					$i++;
				}
			}
			else
			{
					$icons[$group][$i] = false;
			}
		}
		return $icons;
	}
}
