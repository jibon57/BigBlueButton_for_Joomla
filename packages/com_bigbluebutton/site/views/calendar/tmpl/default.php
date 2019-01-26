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

?>
<?php echo $this->toolbar->render(); ?>
<?php

JHtml::_('jquery.framework'); 
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . "media/com_bigbluebutton/css/fullcalendar.min.css");
$document->addScript(JURI::root() . "media/com_bigbluebutton/js/moment.min.js");
$document->addScript(JURI::root() . "media/com_bigbluebutton/js/fullcalendar.min.js");
$itemId = JFactory::getApplication()->getMenu()->getActive()->id;
$config = JFactory::getConfig();
$isActiveSEF = $config->get('sef');

foreach($this->items as $item){
	$alias = $item->id;
	if($isActiveSEF){
		$alias = $item->alias;
	}
	$item->join_url = JRoute::_("index.php?option=com_bigbluebutton&view=eventview&id=".$alias."&Itemid=".$itemId);
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
