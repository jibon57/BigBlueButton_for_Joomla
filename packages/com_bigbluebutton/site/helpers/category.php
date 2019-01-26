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
 * Bigbluebutton Component Category Tree
 */

//Insure this view category file is loaded.
$classname = 'bigbluebuttonMeetingsCategories';
if (!class_exists($classname))
{
	$path = JPATH_SITE . '/components/com_bigbluebutton/helpers/categorymeetings.php';
	if (is_file($path))
	{
		include_once $path;
	}
}
//Insure this view category file is loaded.
$classname = 'bigbluebuttonEventsCategories';
if (!class_exists($classname))
{
	$path = JPATH_SITE . '/components/com_bigbluebutton/helpers/categoryevents.php';
	if (is_file($path))
	{
		include_once $path;
	}
}
