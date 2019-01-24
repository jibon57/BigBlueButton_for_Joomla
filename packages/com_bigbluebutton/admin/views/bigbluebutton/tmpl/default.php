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

JHtml::_('behavior.tooltip');

?>
<div id="j-main-container">
	<div class="span9">
		<?php echo JHtml::_('bootstrap.startAccordion', 'dashboard_left', array('active' => 'main')); ?>
			<?php echo JHtml::_('bootstrap.addSlide', 'dashboard_left', 'cPanel', 'main'); ?>
				<?php echo $this->loadTemplate('main');?>
			<?php echo JHtml::_('bootstrap.endSlide'); ?>
		<?php echo JHtml::_('bootstrap.endAccordion'); ?>
	</div>
	<div class="span3">
		<?php echo JHtml::_('bootstrap.startAccordion', 'dashboard_right', array('active' => 'vdm')); ?>
			<?php echo JHtml::_('bootstrap.addSlide', 'dashboard_right', 'Hoicoi Extension Portal', 'vdm'); ?>
				<?php echo $this->loadTemplate('vdm');?>
			<?php echo JHtml::_('bootstrap.endSlide'); ?>
		<?php echo JHtml::_('bootstrap.endAccordion'); ?>
	</div>
</div>