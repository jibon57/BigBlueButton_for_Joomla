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

?>
<form action="<?php echo JRoute::_('index.php?option=com_bigbluebutton'); ?>" method="post" name="adminForm" id="adminForm">
<?php echo $this->toolbar->render(); ?><?php
$itemId = JFactory::getApplication()->getMenu()->getActive()->id;
?>
<?php 
$config = JFactory::getConfig();
$isActiveSEF = $config->get('sef');

foreach ($this->items as $item):
$alias = $item->id;
if($isActiveSEF){
	$alias = $item->alias;
}
?>
<div id="bbbMeeting" class="bbbMeeting">
	<div class="bbb-heading">
		<h1 class="bbb-page-heading">
			<span class="title"><a href="<?php echo JRoute::_("index.php?option=com_bigbluebutton&view=eventview&id=".$alias."&Itemid=".$itemId)?>" ><?php echo $item->event_title; ?></a></span>
		</h1>
	</div>
	
	<div id="bbb-details" class="bbb-details">
		<div class="bbb-description">
			<?php echo $item->event_des; ?>
		</div>
		
		<div class="detailsLink">
			<a class="btn btn-success" href="<?php echo JRoute::_("index.php?option=com_bigbluebutton&view=eventview&id=".$alias."&Itemid=".$itemId)?>" ><?php echo JText::_('COM_BIGBLUEBUTTON_DETAILS'); ?></a>
		</div>
	</div>
</div>
<?php endforeach; ?>  

<?php if (isset($this->items) && isset($this->pagination) && isset($this->pagination->pagesTotal) && $this->pagination->pagesTotal > 1): ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> <?php echo $this->pagination->getLimitBox(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
