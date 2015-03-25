<?php
/**
 * @version 1
 * @package    joomla
 * @subpackage Bigbluebutton
 * @author	   	Jibon Lawrence Costa
 *  @copyright  	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
 *  @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('Resrtricted Access');

$controller = JControllerLegacy::getInstance('bigbluebutton');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
