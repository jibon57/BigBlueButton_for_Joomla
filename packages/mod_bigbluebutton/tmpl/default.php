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

defined('_JEXEC') or die('Restricted access');	

JHtml::_('jquery.framework');
 
JFactory::getDocument()->addScriptDeclaration('
	jQuery("document").ready(function($){

		var mdMeetings = \''. json_encode($items) .'\';
		mdMeetings = JSON.parse(mdMeetings);

		$("#bbbLoginModuleFrom").submit(function(e){
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				method: "GET",
				dataType: "jsonp",
				url: "index.php?"+data,
				jsonp: "callback",
				
				beforeSend: function(){
					$("#statusModule").html("<img style=\"height: 60px; width: 60px;\" src=\''.JURI::root().'components/com_bigbluebutton/assets/images/ajax.gif\' alt=\'loading..\'/>");
				},
				success: function(res){
					$("#statusModule").html("");
					if(res.status){
						$("#statusModule").html("'.JText::_("COM_BIGBLUEBUTTON_REDIRECTING").'....");
						window.location = res.url;
					}else{
						$("#statusModule").html("'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'");
					}
				},
				error: function(res){
					$("#statusModule").html("'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'");
				}
			})
		});

		$("#moduleCategoryid").on("change", function(e){
			var catid = $(this).val();
			mdGetMeetings(catid);
		});

		var initVal = $("#moduleCategoryid").val();
		if(initVal){
			mdGetMeetings(initVal);
		}

		function mdGetMeetings(catid){

			$("#ModuleMeetingId").find("option").remove();

			for(var i = 0; i < mdMeetings.length; i++){
				var met = mdMeetings[i];

				if(met.catid == catid){

					$("#ModuleMeetingId")
					.append($("<option></option>")
                    .attr("value", met.id)
                    .text(met.title)); 

				}
			}
		}
	})
');
?>
<form id="bbbLoginModuleFrom" class="<?php echo $params->get( 'classname'); ?> uk-form uk-form-horizontal">
    <fieldset>
		<div style="color: red; margin-bottom: 10px;" id="statusModule"></div>
		<?php if($enableCat == 1): ?>
        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_CATEGORY'); ?></label>
			<div class="uk-form-controls">
				<?php 
				 $catOptions = JHtml::_('category.options', 'com_bigbluebutton.meetings'); 
				 echo JHtmlSelect::genericlist($catOptions, 'moduleCategoryid', 'class="moduleCategoryid"', 'value', 'text');
				?>
			</div>
		</div>
		<?php endif; ?>

        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_ROOM'); ?></label>
			<div class="uk-form-controls">
				<?php if($enableCat == 1): ?>
					<select id="ModuleMeetingId" name="meetingId" class="meeting"></select>
				<?php endif; ?>
				<?php 
				if($enableCat == 0){
					echo JHtmlSelect::genericlist($options, 'meetingId', 'class="meeting"', 'value', 'text'); 
				}
				?>
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