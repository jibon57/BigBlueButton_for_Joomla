<?php
/**
 * @version		$Id:extensions.php 1 2015-03-05Z Jibon $
 * @author	   	Jibon Lawrence Costa
 * @package    Bigbluebutton
 * @subpackage Controllers
 * @copyright  	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;


require_once (JPATH_ADMINISTRATOR.'/components/com_bigbluebutton/helpers/bigbluebutton.php' );

class JElementExtensions extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Extensions';

	function fetchElement($name, $value, &$node, $control_name)
	{
	
		$extensions = BigbluebuttonHelper::getExtensions();
		$options = array();
		foreach ($extensions as $extension) {   
		
			$option = new stdClass();
			$option->text = JText::_(ucfirst((string) $extension->name));
			$option->value = (string) $extension->name;
			$options[] = $option;
			
		}		
		
		return JHTML::_('select.genericlist', $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name );
	}
}