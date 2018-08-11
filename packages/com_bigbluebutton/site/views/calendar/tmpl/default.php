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

JHtml::_('jquery.framework'); 
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . "media/com_bigbluebutton/css/fullcalendar.min.css");
$document->addScript(JURI::root() . "media/com_bigbluebutton/js/moment.min.js");
$document->addScript(JURI::root() . "media/com_bigbluebutton/js/fullcalendar.min.js");
$itemId = JFactory::getApplication()->getMenu()->getActive()->id;
foreach($this->items as $item){
	$item->join_url = rtrim(JURI::root(), '/').JRoute::_("index.php?option=com_bigbluebutton&view=eventview&id=".$item->id."&Itemid=".$itemId);
}
?>
<script>
	jQuery('document').ready(function($){
		var items = <?php echo json_encode($this->items) ?>;
		var events = [];
		
		for (var data in items){
			
			var item = {};
			item.title  = items[data].event_title;
			item.start  = items[data].event_start;
			item.end  = items[data].event_end;
			item.url  = items[data].join_url;
			
			events.push(item);
		}

		$('#bbbEventCalendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			events: events
		})
	})
</script>
<div id='bbbEventCalendar'></div>

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
