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
 
defined('_JEXEC') or die('Restricted access'); // no direct access

$lang = JFactory::getLanguage();
$extension = 'com_bigbluebutton';
$base_dir = JPATH_SITE;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query = "SELECT id,title FROM `#__bigbluebutton_meeting` ORDER BY `meetingId` ASC";
$db->setQuery($query);

$items = $db->loadObjectList();

$options = array();
$options[] = JHtml::_('select.option', '', JText::_('COM_BIGBLUEBUTTON_SELECT_MEETING_ROOM'));

foreach($items as $item){
	$options[] = JHtml::_('select.option', $item->id, $item->title);
}

$meetingSelectOption = JHtmlSelect::genericlist($options, 'meetingId', 'class="meeting"', 'value', 'text');

require(JModuleHelper::getLayoutPath('mod_bigbluebutton'));
?>
