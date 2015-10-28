<?php
/**
* @version		$Id:edit.php 1 2015-03-05 16:31:34Z Jibon $
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Set toolbar items for the page
$edit		= JFactory::getApplication()->input->get('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_( 'Meeting' ).': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::apply('meeting.apply');
JToolBarHelper::save('meeting.save');
if (!$edit) {
	JToolBarHelper::cancel('meeting.cancel');
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'meeting.cancel', 'Close' );
}
?>

<script language="javascript" type="text/javascript">


Joomla.submitbutton = function(task)
{
	if (task == 'meeting.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}

</script>

	 	<form method="post" action="<?php echo JRoute::_('index.php?option=com_bigbluebutton&layout=edit&id='.(int) $this->item->id);  ?>" id="adminForm" name="adminForm">
	 	<div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-60  <?php endif; ?>span8 form-horizontal fltlft">
		  <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
				
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('meetingId'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('meetingId');  ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('meetingName'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('meetingName');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('meetingVersion'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('meetingVersion');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('moderatorPW'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('moderatorPW');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('attendeePW'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('attendeePW');  ?>
					</div>
				</div>		
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('voiceBridge'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('voiceBridge');  ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('maxParticipants'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('maxParticipants');  ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('record'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('record');  ?>
					</div>
				</div>	
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('duration'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('duration');  ?>
					</div>
				</div>		
						
          </fieldset>                      
        </div>
        <div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-30  <?php endif; ?>span2 fltrgt">
			        

        </div>                   
		<input type="hidden" name="option" value="com_bigbluebutton" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="meeting" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
