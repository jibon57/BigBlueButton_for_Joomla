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
?>
<?php if ($this->canDo->get('recording.access')): ?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task === 'recording.back') {
			parent.history.back();
			return false;
		} else {
			var form = document.getElementById('adminForm');
			form.task.value = task;
			form.submit();
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_bigbluebutton&view=recording'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
</form><?php 
JHtml::_('jquery.framework'); 
JFactory::getDocument()->addScriptDeclaration('
	jQuery("document").ready(function($){
		$("#bbbRecordingFrom").submit(function(e){
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				method: "GET",
				dataType: "jsonp",
				url: "index.php?"+data,
				jsonp: "callback",
				
				beforeSend: function(){
					$("#statusIndicator").html("<img style=\"height: 60px; width: 60px;\" src=\"'.JURI::root().'components/com_bigbluebutton/assets/images/ajax.gif\" alt=\'loading..\'/>");
					$("#recordingTable #getdata").html("");
				},
				success: function(res){
					$("#statusIndicator").html("");
					if(res.status){
						var items = res.items;
						if(items.length > 0){
							for (i = 0; i < items.length; i++) {
								var item = items[i];
								if(item.playback.format.url !== undefined){
									var element = "<tr>";
									element +="<td>"+item.startTime+"</td>";
									element +="<td>"+item.endTime+"</td>";
									element +="<td> <a href=\'"+item.playback.format.url+"\' target=\'_blank\'>'.JText::_("COM_BIGBLUEBUTTON_PLAYBACK").'</a></td>";
									element +="<td><button class=\'recordDelete btn btn-danger\' id=\'"+item.recordID+"\' >'.JText::_("COM_BIGBLUEBUTTON_DELETE").'</button></td>";
									element += "</tr>";
									$("#recordingTable #getdata").append(element);
								}
							} 
						}else {
							$("#noRecord").html("'.JText::_("COM_BIGBLUEBUTTON_NO_RECORDING_FOUND").'");
						}
					}else{
						$("#statusIndicator").html("'.JText::_("COM_BIGBLUEBUTTON_DIDNT_GET_ANY_RECORDING").'");
					}
				},
				error: function(res){
					$("#statusIndicator").html("'.JText::_("COM_BIGBLUEBUTTON_SOMETHING_WRONG").'");
				}
			})
		})
		
		$(document).on("click", ".recordDelete", function(e){
			var recordId = $(this).attr("id");
			
			var d = confirm("'.JText::_("COM_BIGBLUEBUTTON_CONFIRM_DELETE").'");
			
			if (d == true) {
				var url = "index.php?option=com_bigbluebutton&task=ajax.deleteRecording&format=json&token='.JSession::getFormToken().'&recordingId="+recordId;
				$.ajax({
					method: "GET",
					dataType: "jsonp",
					url: url,
					jsonp: "callback",
					
					beforeSend: function(){
						$("#statusIndicator").html("<img style=\"height: 60px; width: 60px;\" src=\"'.JURI::root().'components/com_bigbluebutton/assets/images/ajax.gif\" alt=\'loading..\'/>");
					},
					success: function(res){
						$("#statusIndicator").html("");
						if(res.status == "true"){
							$("#bbbRecordingFrom").submit();
							alert("'.JText::_("COM_BIGBLUEBUTTON_RECORD_DELETED_SUCCESSFULLY").'");
						}else{
							alert("'.JText::_("COM_BIGBLUEBUTTON_CANT_DELETED_RECORD").'");
						}
					}
				})
			} 
		})
	})
');
 
$options = array();
$options[] = JHtml::_('select.option', '', JText::_('COM_BIGBLUEBUTTON_SELECT_MEETING_ROOM'));
foreach($this->items as $item){
	$options[] = JHtml::_('select.option', $item->id, $item->title);
}
?>
<div class="row-fluid">
	<div style="width: 500px; margin: 0 auto;">
		<div style="color: red; margin-bottom: 10px;" id="statusIndicator"></div>
		<form id="bbbRecordingFrom" class="uk-form uk-form-horizontal">
			<div class="uk-form-row">
				<?php echo JHtmlSelect::genericlist($options, 'meetingId', 'class="meeting"', 'value', 'text'); ?>
				<button type="submit" class="btn btn-success"><?php echo JText::_('COM_BIGBLUEBUTTON_GET'); ?></button>
			</div>
			<input type="hidden" name="option" value="com_bigbluebutton">
			<input type="hidden" name="task" value="ajax.getRecording">
			<input type="hidden" name="format" value="json">
			<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>">
		</form>
	</div>
</div>
<table id="recordingTable" class="table table-hover">
	<tr>
		<th><?php echo JText::_('COM_BIGBLUEBUTTON_STARTED'); ?></th>
		<th><?php echo JText::_('COM_BIGBLUEBUTTON_ENDED'); ?></th>
		<th><?php echo JText::_('COM_BIGBLUEBUTTON_PLAYBACK_URL'); ?></th>
		<th><?php echo JText::_('COM_BIGBLUEBUTTON_ACTION'); ?></th>
	</tr>
	<tbody id="getdata"></tbody> 
</table>
<div id="noRecord" style="color: red"></div>

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
<?php else: ?>
        <h1><?php echo JText::_('COM_BIGBLUEBUTTON_NO_ACCESS_GRANTED'); ?></h1>
<?php endif; ?>
