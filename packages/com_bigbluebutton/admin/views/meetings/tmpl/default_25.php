<?php
/**
* @version		$Id:default_25.php 1 2015-03-05 16:31:34Z Jibon $
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
  JFactory::getDocument()->addStyleSheet(JURI::base().'/components/com_bigbluebutton/assets/lists-j25.css');
  $user		= JFactory::getUser();
  $userId		= $user->get('id');
  $listOrder	= $this->escape($this->state->get('list.ordering'));
  $listDirn	= $this->escape($this->state->get('list.direction'));  
  
  require_once JPATH_ROOT.'/administrator/components/com_bigbluebutton/helpers/bigbluebutton.php';

$bbb = new BigbluebuttonHelper();  
?>
<?php 
$params = JComponentHelper::getParams('com_bigbluebutton');
if ($params->get('salt') == "" || $params->get('url') == "" ) {
	echo '<div class="alert alert-danger"><p class="bg-danger" style="color: red;">Please add server url & salt. You can add those information by click on "Options" button.</p></div>';
}
?>
<form action="index.php?option=com_bigbluebutton&amp;view=meeting" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<div id="filter-bar" class="btn-toolbar">
					<div class="filter-search btn-group pull-left">
						<label class="element-invisible" for="filter_search"><?php echo JText::_( 'Filter' ); ?>:</label>
						<input type="text" name="search" id="search" value="<?php  echo $this->escape($this->state->get('filter.search'));?>" class="text_area" onchange="document.adminForm.submit();" />
					</div>
					<div class="btn-group pull-left">
						<button class="btn" onclick="this.form.submit();"><?php if(version_compare(JVERSION,'3.0','lt')): echo JText::_( 'Go' ); else: ?><i class="icon-search"></i><?php endif; ?></button>
						<button type="button" class="btn" onclick="document.getElementById('search').value='';this.form.submit();"><?php if(version_compare(JVERSION,'3.0','lt')): echo JText::_( 'Reset' ); else: ?><i class="icon-remove"></i><?php endif; ?></button>
					</div>
				</div>					
			</td>
			<td nowrap="nowrap">
		
			</td>
		</tr>		
	</table>
<div id="editcell">
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				
				<th width="20">				
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'Id', 'a.id', $listDirn, $listOrder ); ?>
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
			<td colspan="12">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
<?php
  $k = 0;
  if (count( $this->items ) > 0 ):
  
  for ($i=0, $n=count( $this->items ); $i < $n; $i++):
  
  	$row = $this->items[$i];
 	$onclick = "";

    if (JFactory::getApplication()->input->get('function', null)) {
    	$onclick= "onclick=\"window.parent.jSelectMeeting_id('".$row->id."', '".$this->escape($row->meetingName)."', '','id')\" ";
    }  	
    
 	$link = JRoute::_( 'index.php?option=com_bigbluebutton&view=meeting&task=meeting.edit&id='. $row->id );
 	$row->id = $row->id;
 	
	$checked = JHTML::_('grid.id', $i, $row->id);
	
  	
 	
  ?>
	<tr class="<?php echo "row$k"; ?>">
		
		
        
        <td><?php echo $checked  ?></td>
		<td style="text-align: center;"><?php echo $row->id; ?></td>
	<td style="text-align: center;">
						
							<a <?php echo $onclick; ?>href="<?php echo $link; ?>"><?php echo $row->meetingName; ?></a>
								
		</td>	
	
			<td style="text-align: center;"><?php echo $row->moderatorPW; ?></td>		
	
			<td style="text-align: center;"><?php echo $row->attendeePW; ?></td>		
	
			<td style="text-align: center;"><?php if ($bbb->isMeetingRunning($row->id) == 'true') {
								echo "Yes (<a href='".JURI::base()."index.php?option=com_bigbluebutton&task=endMeeting&meetingId=".$row->id."'>End Now</a>)";
							}
							else {
								echo "No";
							} ?></td>		
	
					
	
	</tr>		
<?php
  $k = 1 - $k;
  endfor;
  else:
  ?>
	<tr>
		<td colspan="12">
			<?php echo JText::_( 'NO_ITEMS_PRESENT' ); ?>
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
