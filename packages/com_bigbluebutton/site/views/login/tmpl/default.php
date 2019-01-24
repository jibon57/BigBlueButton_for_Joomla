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
<?php echo $this->toolbar->render(); ?>
<?php 
//JText::_('COM_BIGBLUEBUTTON_SELECT_MEETING_ROOM');
JHtml::_('jquery.framework'); 
JFactory::getDocument()->addScriptDeclaration('
	jQuery("document").ready(function($){
		var meetings = \''. json_encode($this->items) .'\';
		meetings = JSON.parse(meetings);
		$("#bbbLoginForm").submit(function(e){
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				method: "GET",
				dataType: "jsonp",
				url: "index.php?"+data,
				jsonp: "callback",
				
				beforeSend: function(){
					$("#status").html("<img style=\"height: 60px; width: 60px;\" src=\''.JURI::root().'components/com_bigbluebutton/assets/images/ajax.gif\' alt=\'loading..\'/>");
				},
				success: function(res){
					$("#status").html("");
					if(res.status){
						$("#status").html("'.JText::_("COM_BIGBLUEBUTTON_REDIRECTING").'....");
						window.location = res.url;
					}else{
						$("#status").html("'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'");
					}
				},
				error: function(res){
					$("#status").html("'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'");
				}
			})
		});

		$("#categoryid").on("change", function(e){
			var catid = $(this).val();
			getMeetings(catid);
		});

		var initVal = $("#categoryid").val();
		if(initVal){
			getMeetings(initVal);
		}

		function getMeetings(catid){

			$("#meetingId").find("option").remove();

			for(var i = 0; i < meetings.length; i++){
				var met = meetings[i];
				if(met.catid == catid){

					$("#meetingId")
					.append($("<option></option>")
                    .attr("value", met.id)
                    .text(met.title)); 

				}
			}
		}
	})
');
?>
<form action="" ethod="post" name="bbbLoginForm" id="bbbLoginForm">
    <fieldset>
        <legend><?php echo JText::_('COM_BIGBLUEBUTTON_MEEETING_LOGIN_FORM'); ?></legend>
		<div style="color: red; margin-bottom: 10px;" id="status"></div>

        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_CATEGORY'); ?></label>
			<div class="uk-form-controls">
				<?php 
				 $catOptions = JHtml::_('category.options', 'com_bigbluebutton.meetings'); 
				 echo JHtmlSelect::genericlist($catOptions, 'categoryid', 'class="category"', 'value', 'text');
				?>
			</div>
		</div>

        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_ROOM'); ?></label>
			<div class="uk-form-controls">
				<select id="meetingId" name="meetingId" class="meeting"></select>
			</div>
		</div>
		
        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?></label>
			<div class="uk-form-controls">
				<input name="name" required type="text" placeholder="<?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?>">
			</div>
		</div>
		
		<div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_PASSWORD'); ?></label>
			<div class="uk-form-controls">
				<input type="password" required name="password" placeholder="<?php echo JText::_('COM_BIGBLUEBUTTON_PASSWORD'); ?>">
			</div>
		</div>
		<input type="hidden" name="option" value="com_bigbluebutton">
		<input type="hidden" name="task" value="ajax.login">
		<input type="hidden" name="format" value="json">
		<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>">
		<div class="uk-form-row">
			<div class="uk-form-controls">
				<button class="btn btn-success" type="submit"><?php echo JText::_('COM_BIGBLUEBUTTON_LOGIN'); ?></button>
				<button class="btn btn-danger" type="reset"><?php echo JText::_('COM_BIGBLUEBUTTON_RESET'); ?></button>
			</div>
		</div>
   </fieldset>
</form>  
