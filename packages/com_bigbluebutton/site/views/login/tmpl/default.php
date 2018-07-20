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
<?php echo $this->toolbar->render(); ?> <?php 
$options = array();
$options[] = JHtml::_('select.option', '', JText::_('COM_BIGBLUEBUTTON_SELECT_MEETING_ROOM'));

foreach($this->items as $item){
	$options[] = JHtml::_('select.option', $item->id, $item->title);
}

JHtml::_('jquery.framework'); 
JFactory::getDocument()->addScriptDeclaration('
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
<form id="bbbLoginFrom" class="uk-form uk-form-horizontal">
    <fieldset>
        <legend><?php echo JText::_('COM_BIGBLUEBUTTON_MEEETING_LOGIN_FORM'); ?></legend>
		<div style="color: red; margin-bottom: 10px;" id="status"></div>

        <div class="uk-form-row">
			<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_ROOM'); ?></label>
			<div class="uk-form-controls">
				<?php echo JHtmlSelect::genericlist($options, 'meetingId', 'class="meeting"', 'value', 'text'); ?>
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

<?php if (isset($this->items) && isset($this->pagination) && isset($this->pagination->pagesTotal) && $this->pagination->pagesTotal > 1): ?>
<form name="adminForm" method="post">
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> <?php echo $this->pagination->getLimitBox(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</form>
<?php endif; ?> 
