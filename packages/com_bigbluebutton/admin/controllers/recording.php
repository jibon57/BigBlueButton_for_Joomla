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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Recording Controller
 */
class BigbluebuttonControllerRecording extends JControllerAdmin
{
	protected $text_prefix = 'COM_BIGBLUEBUTTON_RECORDING';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Recording', $prefix = 'BigbluebuttonModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

        public function dashboard()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_bigbluebutton', false));
		return;
	}
}
