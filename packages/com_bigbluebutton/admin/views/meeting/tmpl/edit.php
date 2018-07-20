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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_bigbluebutton');
?>
<script type="text/javascript">
	// waiting spinner
	var outerDiv = jQuery('body');
	jQuery('<div id="loading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('components/com_bigbluebutton/assets/images/import.gif') 50% 15% no-repeat")
		.css("top", outerDiv.position().top - jQuery(window).scrollTop())
		.css("left", outerDiv.position().left - jQuery(window).scrollLeft())
		.css("width", outerDiv.width())
		.css("height", outerDiv.height())
		.css("position", "fixed")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.css("display", "none")
		.appendTo(outerDiv);
	jQuery('#loading').show();
	// when page is ready remove and show
	jQuery(window).load(function() {
		jQuery('#bigbluebutton_loader').fadeIn('fast');
		jQuery('#loading').hide();
	});
</script>
<div id="bigbluebutton_loader" style="display: none;">
<form action="<?php echo JRoute::_('index.php?option=com_bigbluebutton&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

<div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'meetingTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'meetingTab', 'details', JText::_('COM_BIGBLUEBUTTON_MEETING_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('meeting.details_left', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('core.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'meetingTab', 'publishing', JText::_('COM_BIGBLUEBUTTON_MEETING_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('meeting.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('meeting.metadata', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'meetingTab', 'permissions', JText::_('COM_BIGBLUEBUTTON_MEETING_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="meeting.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</div>
</form>
</div>

<script type="text/javascript">

// #jform_record listeners for record_vvvvvvv function
jQuery('#jform_record').on('keyup',function()
{
	var record_vvvvvvv = jQuery("#jform_record").val();
	vvvvvvv(record_vvvvvvv);

});
jQuery('#adminForm').on('change', '#jform_record',function (e)
{
	e.preventDefault();
	var record_vvvvvvv = jQuery("#jform_record").val();
	vvvvvvv(record_vvvvvvv);

});

// #jform_branding listeners for branding_vvvvvvw function
jQuery('#jform_branding').on('keyup',function()
{
	var branding_vvvvvvw = jQuery("#jform_branding input[type='radio']:checked").val();
	vvvvvvw(branding_vvvvvvw);

});
jQuery('#adminForm').on('change', '#jform_branding',function (e)
{
	e.preventDefault();
	var branding_vvvvvvw = jQuery("#jform_branding input[type='radio']:checked").val();
	vvvvvvw(branding_vvvvvvw);

});

</script>
