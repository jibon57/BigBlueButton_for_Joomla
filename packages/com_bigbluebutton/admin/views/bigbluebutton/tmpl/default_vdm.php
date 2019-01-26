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

?>
<img alt="<?php echo JText::_('COM_BIGBLUEBUTTON'); ?>" src="components/com_bigbluebutton/assets/images/vdm-component.png">
<ul class="list-striped">
	<li><b><?php echo JText::_('COM_BIGBLUEBUTTON_VERSION'); ?>:</b> <?php echo $this->manifest->version; ?>&nbsp;&nbsp;<span class="update-notice"></span></li>
	<li><b><?php echo JText::_('COM_BIGBLUEBUTTON_DATE'); ?>:</b> <?php echo $this->manifest->creationDate; ?></li>
	<li><b><?php echo JText::_('COM_BIGBLUEBUTTON_AUTHOR'); ?>:</b> <a href="mailto:<?php echo $this->manifest->authorEmail; ?>"><?php echo $this->manifest->author; ?></a></li>
	<li><b><?php echo JText::_('COM_BIGBLUEBUTTON_WEBSITE'); ?>:</b> <a href="<?php echo $this->manifest->authorUrl; ?>" target="_blank"><?php echo $this->manifest->authorUrl; ?></a></li>
	<li><b><?php echo JText::_('COM_BIGBLUEBUTTON_LICENSE'); ?>:</b> <?php echo $this->manifest->license; ?></li>
	<li><b><?php echo $this->manifest->copyright; ?></b></li>
</ul>
<div class="clearfix"></div>
<?php if(BigbluebuttonHelper::checkArray($this->contributors)): ?>
	<?php if(count($this->contributors) > 1): ?>
		<h3><?php echo JText::_('COM_BIGBLUEBUTTON_CONTRIBUTORS'); ?></h3>
	<?php else: ?>
		<h3><?php echo JText::_('COM_BIGBLUEBUTTON_CONTRIBUTOR'); ?></h3>
	<?php endif; ?>
	<ul class="list-striped">
		<?php foreach($this->contributors as $contributor): ?>
		<li><b><?php echo $contributor['title']; ?>:</b> <?php echo $contributor['name']; ?></li>
		<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
<?php endif; ?>