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
JHtml::_('behavior.tabstate');

// Set the component css/js
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_bigbluebutton/assets/css/site.css');
$document->addScript('components/com_bigbluebutton/assets/js/site.js');

// Require helper files
JLoader::register('BigbluebuttonHelper', __DIR__ . '/helpers/bigbluebutton.php'); 
JLoader::register('BigbluebuttonHelperRoute', __DIR__ . '/helpers/route.php'); 

// Get an instance of the controller prefixed by Bigbluebutton
$controller = JControllerLegacy::getInstance('Bigbluebutton');

// Perform the request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
