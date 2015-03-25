<?php
/**
 * @version 1.2
 * @package    joomla
 * @subpackage Bigbluebutton
 * @author	   	Jibon Lawrence Costa
 *  @copyright  	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
 *  @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access'); // no direct access

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query = "SELECT * FROM `#__bbb_meetings`";
$db->setQuery($query);
$result = $db->loadAssocList();
require(JModuleHelper::getLayoutPath('mod_bigbluebutton'));
?>
