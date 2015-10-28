 <?php
/**
* @version		$Id:default.php 1 2015-03-05 16:31:34Z Jibon $
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
require_once JPATH_ROOT.'/administrator/components/com_bigbluebutton/helpers/bigbluebutton.php';

$bbb = new BigbluebuttonHelper();

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$params		= (isset($this->state->params)) ? $this->state->params : new JObject;
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_bigbluebutton&task=meetings.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

?>

<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<?php 
$params = JComponentHelper::getParams('com_bigbluebutton');
if ($params->get('salt') == "" || $params->get('url') == "" ) {
	echo '<div class="alert alert-danger"><p class="bg-danger">Please add server url & salt. You can add those information by click on "Options" button.</p></div>';
}
?>
<form action="index.php?option=com_bigbluebutton&view=meeting" method="post" name="adminForm" id="adminForm">

	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">				
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>

	
<div id="editcell">
	<table class="adminlist table table-striped" id="articleList">
		<thead>
			<tr>
				
					
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="(<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title">
					<?php //echo JHTML::_('grid.sort', 'Id', 'a.id', $listDirn, $listOrder ); ?>
				</th>			
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'MeetingId', 'a.meetingId', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'MeetingName', 'a.meetingName', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'ModeratorPW', 'a.moderatorPW', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'AttendeePW', 'a.attendeePW', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo "Is Meeting Running?" ?>
				</th>
								
							</tr> 			
		</thead>
		<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
<?php
  if (count($this->items)) : 
  		foreach ($this->items as $i => $item) :
																$canCreate  = $user->authorise('core.create');
				$canEdit    = $user->authorise('core.edit');				
				$canChange  = $user->authorise('core.edit.state'); 				
					
				$disableClassName = '';
				$disabledLabel	  = '';
				if (!$saveOrder) {
					$disabledLabel    = JText::_('JORDERINGDISABLED');
					$disableClassName = 'inactive tip-top';
				} 
	
 				$onclick = "";
  	
    			if (JFactory::getApplication()->input->get('function', null)) {
    				$onclick= "onclick=\"window.parent.jSelectMeeting_id('".$item->id."', '".$this->escape($item->meetingName)."', '','id')\" ";
    			}  	
    
 				$link = JRoute::_( 'index.php?option=com_bigbluebutton&view=meeting&task=meeting.edit&id='. $item->id );
 	
 				
 	
 				$checked = JHTML::_('grid.id', $i, $item->id);
 	 	
  		?>
				<tr class="row<?php echo $i % 2; ?>"">
					      
        			<td><?php echo $checked;  ?></td>
        			<td><?php //echo $item->id; ?></td>
					<td><?php echo $item->meetingId; ?></td>
        				
									        <td class="nowrap has-context">
									        
					<div class="pull-left">
					
														<?php if ($canEdit) : ?>
								<a href="<?php  echo $link; ?>">
									<?php  echo $this->escape($item->meetingName); ?></a>
							<?php  else : ?>
								<?php  echo $this->escape($item->meetingName); ?>
							<?php  endif; ?>
							
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'meeting.');
																
								// render dropdown list
								echo JHtml::_('dropdown.render');
								?>
						</div>
						</td>
								 		
						<td><?php echo $item->moderatorPW; ?></td>
								 		
						<td><?php echo $item->attendeePW; ?></td>
								 		
						<td><?php 
							if ($bbb->isMeetingRunning($item->meetingId) == 'true') {
								echo "Yes (<a href='".JURI::base()."index.php?option=com_bigbluebutton&task=endMeeting&meetingId=".$item->meetingId."'>End Now</a>)";
							}
							else {
								echo "No";
							}
						?></td>
								 		
						
						</tr>
<?php

  endforeach;
  else:
  ?>
	<tr>
		<td colspan="12">
			<?php echo JText::_( 'There are no items present' ); ?>
		</td>
	</tr>
	<?php
  endif;
  ?>
</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_bigbluebutton" />
<input type="hidden" name="task" value="meeting" />
<input type="hidden" name="view" value="meetings" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>  	
