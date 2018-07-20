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

?>
<?php echo $this->toolbar->render(); ?> 
<?php 
JHtml::_('jquery.framework'); 
$doc = JFactory::getDocument();
$doc->addStyleSheet('media/jui/css/bootstrap.min.css');
$doc->addScriptDeclaration('
	jQuery("document").ready(function($){
		$("#bbbLoginFrom").submit(function(e){
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
		})
	})
');
?>
<div id="bbbMeeting" class="bbbMeeting">
	<div class="bbb-heading">
		<h1 class="bbb-page-heading">
			<span class="title"><?php echo $this->item->title; ?></span>
		</h1>
	</div>
	
	<div id="bbb-details" class="bbb-details">
		<div class="bbb-description">
			<?php echo $this->item->description; ?>
		</div>
		<div class="loginFrom">
			<form id="bbbLoginFrom" class="uk-form uk-form-horizontal">
				<fieldset>
					<legend><?php echo JText::_('COM_BIGBLUEBUTTON_MEEETING_LOGIN_FORM'); ?></legend>
					<div style="color: red; margin-bottom: 10px;" id="status"></div>

					<div class="uk-form-row">
						<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?></label>
						<div class="uk-form-controls">
							<input name="name" type="text" required placeholder="<?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?>">
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
					<input type="hidden" name="meetingId" value="<?php echo $this->item->id; ?>">
					<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>">
					<div class="uk-form-row">
						<div class="uk-form-controls">
							<button class="btn btn-success" type="submit"><?php echo JText::_('COM_BIGBLUEBUTTON_LOGIN'); ?></button>
							<button class="btn btn-danger" type="reset"><?php echo JText::_('COM_BIGBLUEBUTTON_RESET'); ?></button>
						</div>
					</div>
			   </fieldset>
			</form>  
		</div>
	</div>
</div>  
