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
 * Bigbluebutton Event Component Category Tree
 */
class BigbluebuttonEventsCategories extends JCategories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__bigbluebutton_event';
		$options['extension'] = 'com_bigbluebutton.events';

		parent::__construct($options);
	}
}
