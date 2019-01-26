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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tabstate');
JHtml::_('behavior.calendar');
$componentParams = $this->params; // will be removed just use $this->params instead
?>
<?php echo $this->toolbar->render(); ?>
<form action="<?php echo JRoute::_('index.php?option=com_bigbluebutton&layout=edit&id='. (int) $this->item->id . $this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
<div>

<div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'eventTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'eventTab', 'details', JText::_('COM_BIGBLUEBUTTON_EVENT_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('event.details_left', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php $this->ignore_fieldsets = array('details','metadata','vdmmetadata','accesscontrol'); ?>
	<?php $this->tab_name = 'eventTab'; ?>
	<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

	<?php if ($this->canDo->get('event.delete') || $this->canDo->get('event.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('event.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'eventTab', 'publishing', JText::_('COM_BIGBLUEBUTTON_EVENT_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('event.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('event.metadata', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'eventTab', 'permissions', JText::_('COM_BIGBLUEBUTTON_EVENT_PERMISSION', true)); ?>
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
		<input type="hidden" name="task" value="event.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</div>
</form>

<script type="text/javascript">
jQuery('#jform_timezone').on('keyup',function(){var timezone_vvvvvvx=jQuery("#jform_timezone input[type='radio']:checked").val();vvvvvvx(timezone_vvvvvvx)});jQuery('#adminForm').on('change','#jform_timezone',function(e){e.preventDefault();var timezone_vvvvvvx=jQuery("#jform_timezone input[type='radio']:checked").val();vvvvvvx(timezone_vvvvvvx)});jQuery('#jform_emails').on('keyup',function(){var emails_vvvvvvy=jQuery("#jform_emails").val();vvvvvvy(emails_vvvvvvy)});jQuery('#adminForm').on('change','#jform_emails',function(e){e.preventDefault();var emails_vvvvvvy=jQuery("#jform_emails").val();vvvvvvy(emails_vvvvvvy)})


jQuery('#jform_event_start').datetimepicker({
	format:'Y-m-d H:i',
	step: 15,
	startDate: '<?php echo $this->item->event_start; ?>'
});
jQuery('#jform_event_end').datetimepicker({
	format:'Y-m-d H:i',
	step: 15,
	startDate: '<?php echo $this->item->event_end; ?>'
});
</script>
